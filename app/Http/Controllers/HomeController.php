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
        if ($request->filled('service')) {
            $compiledMessage .= 'Service / Treatment: ' . str_replace('-', ' ', ucwords($request->service)) . "\n";
        }
        if ($request->filled('appointment_date')) {
            $compiledMessage .= 'Preferred Date: ' . $request->appointment_date . "\n";
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

    /* Top Navigation Live Autocomplete Search */
    public function liveSearch(Request $request)
    {
        $query = trim($request->input('q', ''));
        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'count' => 0]);
        }

        $results = [];

        // 1. Search Treatments
        $treatments = \App\Models\Treatment::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            })
            ->take(6)
            ->get();

        foreach ($treatments as $t) {
            $results[] = [
                'type' => 'Treatment',
                'badge_bg' => '#e0f2fe',
                'badge_color' => '#0369a1',
                'title' => $t->name,
                'subtitle' => \Illuminate\Support\Str::limit(strip_tags($t->description), 80),
                'url' => route('treatments.show', $t->slug),
                'image' => site_image($t->image, 'treatment'),
            ];
        }

        // 2. Search Services
        $services = \App\Models\Service::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('service_name', 'LIKE', "%{$query}%")
                  ->orWhere('hero_title', 'LIKE', "%{$query}%")
                  ->orWhere('summary', 'LIKE', "%{$query}%")
                  ->orWhere('intro_content', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get();

        foreach ($services as $s) {
            $results[] = [
                'type' => 'Service',
                'badge_bg' => '#ccfbf1',
                'badge_color' => '#0f766e',
                'title' => $s->service_name,
                'subtitle' => \Illuminate\Support\Str::limit(strip_tags($s->summary ?? $s->hero_subtitle), 80),
                'url' => route('services.show', $s->slug),
                'image' => site_image($s->image_path, 'service'),
            ];
        }

        // 3. Search Specialists
        $specialists = \App\Models\TeamMember::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('designation', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->take(4)
            ->get();

        foreach ($specialists as $sp) {
            $results[] = [
                'type' => 'Specialist',
                'badge_bg' => '#f3e8ff',
                'badge_color' => '#6b21a8',
                'title' => $sp->name,
                'subtitle' => $sp->designation ?? 'Orthopaedic Specialist',
                'url' => route('specialists.show', $sp->slug),
                'image' => site_image($sp->image, 'specialist'),
            ];
        }

        // 4. Search Blogs / Health Hub
        $blogs = \App\Models\Blog::where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('summary', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            })
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        foreach ($blogs as $b) {
            $results[] = [
                'type' => 'Blog',
                'badge_bg' => '#fef3c7',
                'badge_color' => '#92400e',
                'title' => $b->title ?? $b->name,
                'subtitle' => \Illuminate\Support\Str::limit(strip_tags($b->summary ?? $b->content ?? $b->description), 80),
                'url' => route('blog.show', $b->slug),
                'image' => site_image($b->image_path ?? $b->image, 'blog'),
            ];
        }

        return response()->json(['results' => $results, 'count' => count($results)]);
    }
}
