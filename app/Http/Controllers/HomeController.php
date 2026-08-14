<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HomeSlide;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $slides = HomeSlide::orderBy('sort_order')->orderBy('id')->get();
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->get();
        $homePage = \App\Models\CmsPage::where('slug', 'home')->first();
        return view('index', compact('slides', 'testimonials', 'homePage'));
    }

    public function about()
    {
        $aboutPage = \App\Models\CmsPage::where('slug', 'about-us')->first();
        return view('about', compact('aboutPage'));
    }

    public function services()
    {
        $servicesPage = \App\Models\CmsPage::where('slug', 'services')->first();
        $services = \App\Models\Service::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        return view('services', compact('services', 'servicesPage'));
    }

    public function servicesShow($slug)
    {
        $service = \App\Models\Service::where('slug', $slug)->where('is_active', true)->first();
        if ($service) {
            return view('services.show', compact('service'));
        }
        abort(404);
    }


    public function testimonials()
    {
        $testimonialPage = \App\Models\CmsPage::where('slug', 'testimonials')->first();
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->get();
        return view('testimonials', compact('testimonials', 'testimonialPage'));
    }

    public function faq()
    {
        $faqPage = \App\Models\CmsPage::where('slug', 'faqs')->first();
        $services = \App\Models\Service::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        return view('faq', compact('services', 'faqPage'));
    }

    public function blog(Request $request)
    {
        $blogsPage = \App\Models\CmsPage::where('slug', 'blogs')->first();
        $blogs = \App\Models\Blog::orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(9);
        
        if ($request->ajax()) {
            $html = '';
            foreach ($blogs as $blog) {
                $html .= view('blog.partials.card', compact('blog'))->render();
            }
            return response()->json([
                'html' => $html,
                'next_page' => $blogs->nextPageUrl()
            ]);
        }
        
        return view('blog.index', compact('blogs', 'blogsPage'));
    }

    public function blogShow($slug)
    {
        $blog = \App\Models\Blog::where('slug', $slug)->first();
        if ($blog) {
            return view('blog.show', compact('blog'));
        }
        abort(404);
    }

    public function contact()
    {
        $contactPage = \App\Models\CmsPage::where('slug', 'contact-us')->first();
        return view('contact', compact('contactPage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email|max:255',
        ]);

        $siteSettings = \App\Models\SiteParameter::all()->pluck('value', 'id')->toArray();
        
        // 1. Build detailed compilation message
        $compiledMessage = '';
        if ($request->filled('loan_type')) {
            $compiledMessage .= 'Loan Type: ' . ucwords($request->loan_type) . "\n";
        }
        if ($request->filled('amount')) {
            $compiledMessage .= 'Expected Amount: ₹' . number_format($request->amount) . "\n";
        }
        if ($request->filled('service')) {
            $compiledMessage .= 'Service: ' . str_replace('-', ' ', ucwords($request->service)) . "\n";
        }
        if ($request->filled('message')) {
            $compiledMessage .= 'Details: ' . $request->message . "\n";
        }

        // 2. Save Lead immediately to guarantee it is saved in the database FIRST
        // Auto-create leads table if it doesn't exist
        if (!\Illuminate\Support\Facades\Schema::hasTable('leads')) {
            try {
                \Illuminate\Support\Facades\Schema::create('leads', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('phone');
                    $table->string('email')->nullable();
                    $table->text('message')->nullable();
                    $table->string('source')->default('Contact Form');
                    $table->string('status')->default('New');
                    $table->text('notes')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Leads table creation failed: ' . $e->getMessage());
            }
        }

        $lead = \App\Models\Lead::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'message' => $compiledMessage ?: 'Requesting a quick callback/consultation.',
            'source'  => $request->input('source', 'Contact Form'),
            'status'  => 'New',
        ]);

        \App\Models\AdminNotification::log(
            'lead',
            'New Patient Lead: ' . $lead->name,
            'Phone: ' . $lead->phone . ($lead->email ? (' | Email: ' . $lead->email) : ''),
            route('admin.tab', 'leads')
        );

        // 3. Google reCAPTCHA Verification (if enabled in settings)
        $isSpam = false;
        $spamReason = '';
        if (!empty($siteSettings['recaptcha_enabled']) && $siteSettings['recaptcha_enabled'] == '1' && !empty($siteSettings['recaptcha_site_key']) && !empty($siteSettings['recaptcha_secret_key'])) {
            try {
                $recaptchaResponse = $request->input('g-recaptcha-response');
                $secretKey = $siteSettings['recaptcha_secret_key'];
                
                if (empty($recaptchaResponse)) {
                    $isSpam = true;
                    $spamReason = 'reCAPTCHA response checkbox was empty/unchecked.';
                } else {
                    $verifyResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $secretKey,
                        'response' => $recaptchaResponse,
                        'remoteip' => $request->ip()
                    ]);
                    
                    if (!$verifyResponse->successful() || !$verifyResponse->json('success')) {
                        $isSpam = true;
                        $spamReason = 'reCAPTCHA verification failed on Google endpoint.';
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('reCAPTCHA safety check encountered exception: ' . $e->getMessage());
            }

            // Update lead status if it was flagged as spam
            if ($isSpam && $lead) {
                try {
                    $lead->update([
                        'status' => 'Spam',
                        'notes' => 'Flagged: ' . $spamReason
                    ]);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed to flag spam lead: ' . $e->getMessage());
                }
            }
        }

        // 4. Send Email Notification if SMTP settings are fully configured in the settings area
        if (!empty($siteSettings['smtp_host']) && !empty($siteSettings['smtp_username']) && !empty($siteSettings['smtp_password']) && !empty($siteSettings['smtp_to_email'])) {
            try {
                // Dynamically configure Laravel mailer settings
                config([
                    'mail.mailers.smtp.transport' => 'smtp',
                    'mail.mailers.smtp.host' => $siteSettings['smtp_host'],
                    'mail.mailers.smtp.port' => (int)($siteSettings['smtp_port'] ?? 587),
                    'mail.mailers.smtp.username' => $siteSettings['smtp_username'],
                    'mail.mailers.smtp.password' => $siteSettings['smtp_password'],
                    'mail.mailers.smtp.encryption' => ($siteSettings['smtp_encryption'] ?? 'tls') === 'none' ? null : ($siteSettings['smtp_encryption'] ?? 'tls'),
                    'mail.from.address' => $siteSettings['smtp_from_address'] ?? 'no-reply@mlgfinedge.com',
                    'mail.from.name' => $siteSettings['smtp_from_name'] ?? 'MLG Finedge Alerts',
                    'mail.default' => 'smtp',
                ]);

                // Purge SMTP mailer cache to force Laravel to re-read updated runtime configs
                \Illuminate\Support\Facades\Mail::purge('smtp');

                $toEmail = $siteSettings['smtp_to_email'];
                $fromAddress = $siteSettings['smtp_from_address'] ?? 'no-reply@mlgfinedge.com';
                $fromName = $siteSettings['smtp_from_name'] ?? 'MLG Finedge Alerts';
                $leadName = $lead->name;

                \Illuminate\Support\Facades\Mail::raw(
                    "New Lead Inquiry Received" . ($isSpam ? " [Flagged Spam]" : "") . ":\n\n" .
                    "Name: " . $lead->name . "\n" .
                    "Phone: " . $lead->phone . "\n" .
                    "Email: " . ($lead->email ?: 'N/A') . "\n" .
                    "Source: " . ($lead->source) . "\n\n" .
                    "Details:\n" . ($lead->message),
                    function ($message) use ($toEmail, $fromAddress, $fromName, $leadName) {
                        $message->to($toEmail)
                                ->subject('New Lead Inquiry: ' . $leadName)
                                ->from($fromAddress, $fromName);
                    }
                );
                
                \Illuminate\Support\Facades\Log::info('Email alert sent for lead: ' . $leadName);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('SMTP Lead Email Notification failed: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            }
        }

        // 5. Send WhatsApp Notification if WhatsApp API settings are fully configured in the settings area
        if (!empty($siteSettings['whatsapp_api_url']) && !empty($siteSettings['whatsapp_admin_recipient'])) {
            try {
                $apiUrl = $siteSettings['whatsapp_api_url'];
                $apiToken = $siteSettings['whatsapp_api_token'] ?? '';
                $recipient = $siteSettings['whatsapp_admin_recipient'];
                
                $textMessage = "New Lead Alert!" . ($isSpam ? " [Flagged Spam]" : "") . "\n" .
                              "Name: {$lead->name}\n" .
                              "Phone: {$lead->phone}\n" .
                              "Email: " . ($lead->email ?: 'N/A') . "\n" .
                              "Source: {$lead->source}\n" .
                              "Message: " . ($lead->message);
                
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, [
                    'to' => $recipient,
                    'message' => $textMessage,
                    'phone' => $recipient,
                    'body' => $textMessage,
                    'text' => ['body' => $textMessage]
                ]);
                
                if ($response->successful()) {
                    \Illuminate\Support\Facades\Log::info('WhatsApp notification sent successfully for lead: ' . $lead->name);
                } else {
                    \Illuminate\Support\Facades\Log::warning('WhatsApp API returned error code: ' . $response->status() . ' | Response: ' . $response->body());
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('WhatsApp notification failed: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            }
        }

        return redirect()->route('thank-you')->with('success', 'Your inquiry has been submitted successfully.');
    }

    public function landingShow($slug)
    {
        $landingPage = \App\Models\LandingPage::where('slug', $slug)->first();
        if ($landingPage) {
            return view('landing-page', compact('landingPage'));
        }
        abort(404);
    }

    public function thankYou()
    {
        return view('thank-you');
    }

    /* Clinic Specialists */
    public function specialists()
    {
        $specialistsPage = \App\Models\CmsPage::where('slug', 'specialists')->first();
        $specialists = \App\Models\TeamMember::where('status', true)->get();
        return view('specialists.index', compact('specialists', 'specialistsPage'));
    }


    public function specialistdetails($slug)
    {
        $specialist = \App\Models\TeamMember::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('specialists.show', compact('specialist'));
    }

    /* Clinic Treatments */
    public function treatments()
    {
        $treatmentsPage = \App\Models\CmsPage::where('slug', 'treatments')->first();
        $treatments = \App\Models\Treatment::where('status', true)->get();
        return view('treatments.index', compact('treatments', 'treatmentsPage'));
    }

    public function treatmentDetail($slug)
    {
        $treatment = \App\Models\Treatment::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('treatments.show', compact('treatment'));
    }

    /* CMS Pages catch-all */
    public function cmsPageShow($slug)
    {
        $page = \App\Models\CmsPage::where('slug', $slug)->where('status', true)->first();
        if ($page) {
            return view('cms-page', compact('page'));
        }
        abort(404);
    }
}
