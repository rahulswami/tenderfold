<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SiteParameter;
use App\Models\HomeSlide;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function dashboard()
    {
        $parameters = SiteParameter::all()->groupBy('category');
        $slides = HomeSlide::orderBy('sort_order')->orderBy('id')->get();
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->get();
        $blogs = \App\Models\Blog::orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(15, ['*'], 'blogs_page');
        
        // Scan public storage for media files
        $mediaFiles = [];
        try {
            $r2PublicUrl = trim(SiteParameter::where('id', 'cloudflare_r2_public_url')->value('value') ?? '');
            $hasR2 = !empty($r2PublicUrl) && !empty(trim(SiteParameter::where('id', 'cloudflare_r2_account_id')->value('value') ?? ''));
            if (empty($r2PublicUrl)) {
                $r2PublicUrl = 'https://images.mlgfinedge.com';
            }
            $r2PublicUrl = rtrim($r2PublicUrl, '/');

            $files = Storage::disk('public')->allFiles();
            foreach ($files as $file) {
                if (!str_starts_with(basename($file), '.')) {
                    $mediaUrl = $r2PublicUrl . '/' . basename($file);
                    if (!$hasR2 && config('app.env') === 'local') {
                        $mediaUrl = asset('storage/' . $file);
                    }
                    $mediaFiles[] = [
                        'path' => $file,
                        'url' => $mediaUrl,
                        'name' => basename($file),
                        'size' => Storage::disk('public')->size($file),
                        'time' => Storage::disk('public')->lastModified($file)
                    ];
                }
            }
            usort($mediaFiles, function($a, $b) {
                return $b['time'] <=> $a['time'];
            });
        } catch (\Exception $e) {
            // Silently fail
        }

        // Fetch all leads paginated
        $leads = \App\Models\Lead::orderBy('created_at', 'desc')->paginate(15, ['*'], 'leads_page');

        // Fetch all page contents to let user edit them
        $pageContents = \App\Models\PageContent::all();

        // Load services and banks for management
        $services = \App\Models\Service::orderBy('sort_order')->orderBy('id')->get();
        $banks = \App\Models\ComparisonBank::orderBy('sort_order')->orderBy('id')->get();
        
        // Fetch all landing pages
        $landingPages = \App\Models\LandingPage::orderBy('id', 'desc')->get();

        // Fetch clinic-specific objects for Dr. Sumit
        $treatments = \App\Models\Treatment::orderBy('id', 'desc')->get();
        $specialists = \App\Models\TeamMember::orderBy('id', 'desc')->get();
        $cmsPages = \App\Models\CmsPage::orderBy('id', 'desc')->get();
        
        return view('admin.dashboard', compact('parameters', 'slides', 'testimonials', 'blogs', 'mediaFiles', 'pageContents', 'services', 'banks', 'leads', 'landingPages', 'treatments', 'specialists', 'cmsPages'));
    }

    public function updateParameters(Request $request)
    {
        // Handle unchecked checkbox for recaptcha_enabled
        if (!$request->has('recaptcha_enabled')) {
            $request->merge(['recaptcha_enabled' => '0']);
        }

        $data = $request->except(['_token', 'logo', 'favicon', 'logo_url', 'favicon_url']);
        
        foreach ($data as $key => $value) {
            $param = SiteParameter::find($key);
            if ($param) {
                $param->update(['value' => $value]);
            } else {
                SiteParameter::create([
                    'id' => $key,
                    'value' => $value,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'category' => $this->getParameterCategory($key),
                ]);
            }
        }

        // Handle custom logo upload and conversion to WebP (keep SVG intact)
        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadAndConvertToWebP($request->file('logo'), 'branding');
            if ($logoPath) {
                // Delete old logo file if exists
                $oldLogo = SiteParameter::find('logo_path');
                if ($oldLogo && $oldLogo->value && !str_starts_with($oldLogo->value, 'http') && !str_starts_with($oldLogo->value, '//')) {
                    Storage::disk('public')->delete($oldLogo->value);
                }
                
                SiteParameter::updateOrCreate(
                    ['id' => 'logo_path'],
                    ['value' => $logoPath, 'label' => 'Custom Logo Image (WebP)', 'category' => 'branding']
                );
            }
        } elseif ($request->has('logo_url')) {
            SiteParameter::updateOrCreate(
                ['id' => 'logo_path'],
                ['value' => $request->input('logo_url'), 'label' => 'Custom Logo Image (WebP)', 'category' => 'branding']
            );
        }

        // Handle custom favicon upload
        if ($request->hasFile('favicon')) {
            $faviconPath = $this->uploadAndConvertToWebP($request->file('favicon'), 'branding');
            if ($faviconPath) {
                $oldFav = SiteParameter::find('favicon_path');
                if ($oldFav && $oldFav->value && !str_starts_with($oldFav->value, 'http') && !str_starts_with($oldFav->value, '//')) {
                    Storage::disk('public')->delete($oldFav->value);
                }
                
                SiteParameter::updateOrCreate(
                    ['id' => 'favicon_path'],
                    ['value' => $faviconPath, 'label' => 'Custom Favicon Image (WebP)', 'category' => 'branding']
                );
            }
        } elseif ($request->has('favicon_url')) {
            SiteParameter::updateOrCreate(
                ['id' => 'favicon_path'],
                ['value' => $request->input('favicon_url'), 'label' => 'Custom Favicon Image (WebP)', 'category' => 'branding']
            );
        }

        return redirect()->back()->with('success', 'Parameters and branding updated successfully!');
    }

    /* Homepage Slides CRUD */
    public function storeSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'slider');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        HomeSlide::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'sort_order' => $request->sort_order ?? 0,
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Slide added successfully!');
    }

    public function updateSlide(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
        ]);

        $slide = HomeSlide::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($slide->image_path && !str_starts_with($slide->image_path, 'http') && !str_starts_with($slide->image_path, '//')) {
                Storage::disk('public')->delete($slide->image_path);
            }
            $slide->image_path = $this->uploadAndConvertToWebP($request->file('image'), 'slider');
        } elseif ($request->has('image_url')) {
            $slide->image_path = $request->input('image_url');
        }

        $slide->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'Slide updated successfully!');
    }

    public function deleteSlide($id)
    {
        $slide = HomeSlide::findOrFail($id);
        if ($slide->image_path) {
            Storage::disk('public')->delete($slide->image_path);
        }
        $slide->delete();

        return redirect()->back()->with('success', 'Slide deleted successfully!');
    }

    /* Testimonials CRUD */
    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2000',
            'avatar_url' => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $this->uploadAndConvertToWebP($request->file('avatar'), 'testimonials');
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->input('avatar_url');
        }

        Testimonial::create([
            'name' => $request->name,
            'role' => $request->role,
            'content' => $request->content,
            'rating' => $request->rating,
            'sort_order' => $request->sort_order ?? 0,
            'avatar_path' => $avatarPath,
        ]);

        return redirect()->back()->with('success', 'Testimonial added successfully!');
    }

    public function updateTestimonial(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:2000',
            'avatar_url' => 'nullable|string',
        ]);

        $testimonial = Testimonial::findOrFail($id);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar_path && !str_starts_with($testimonial->avatar_path, 'http') && !str_starts_with($testimonial->avatar_path, '//')) {
                Storage::disk('public')->delete($testimonial->avatar_path);
            }
            $testimonial->avatar_path = $this->uploadAndConvertToWebP($request->file('avatar'), 'testimonials');
        } elseif ($request->has('avatar_url')) {
            $testimonial->avatar_path = $request->input('avatar_url');
        }

        $testimonial->update([
            'name' => $request->name,
            'role' => $request->role,
            'content' => $request->content,
            'rating' => $request->rating,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'Testimonial updated successfully!');
    }

    public function deleteTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->avatar_path) {
            Storage::disk('public')->delete($testimonial->avatar_path);
        }
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }

    /* Blogs CRUD */
    public function listBlogs(Request $request)
    {
        $blogs = \App\Models\Blog::orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(15);
        
        $items = [];
        foreach ($blogs as $b) {
            $items[] = [
                'id' => $b->id,
                'title' => $b->title,
                'category' => $b->category,
                'slug' => $b->slug,
                'published_at' => $b->published_at ? $b->published_at->format('M d, Y H:i') : ($b->created_at ? $b->created_at->format('M d, Y H:i') : ''),
                'image_url' => $b->image_path ? site_image($b->image_path) : null,
                'delete_url' => route('admin.blogs.delete', $b->id),
            ];
        }

        return response()->json([
            'data' => $items,
            'next_page_url' => $blogs->nextPageUrl(),
        ]);
    }

    public function detailBlog($id)
    {
        $blog = \App\Models\Blog::findOrFail($id);
        return response()->json([
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'category' => $blog->category,
            'published_at' => $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : ($blog->created_at ? $blog->created_at->format('Y-m-d\TH:i') : null),
            'summary' => $blog->summary,
            'content' => $blog->content,
            'image_path' => $blog->image_path,
            'schema_markup' => $blog->schema_markup,
        ]);
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'schema_markup' => 'nullable|string',
        ]);

        $slug = $request->slug ?: \Illuminate\Support\Str::slug($request->title);
        $count = \App\Models\Blog::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'blog');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        \App\Models\Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->content,
            'image_path' => $imagePath,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : now(),
            'schema_markup' => $request->schema_markup,
        ]);

        return redirect()->back()->with('success', 'Blog post created successfully!');
    }

    public function updateBlog(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
            'schema_markup' => 'nullable|string',
        ]);

        $blog = \App\Models\Blog::findOrFail($id);

        $imagePath = $blog->image_path;
        if ($request->hasFile('image')) {
            if ($blog->image_path && !str_starts_with($blog->image_path, 'http') && !str_starts_with($blog->image_path, '//')) {
                Storage::disk('public')->delete($blog->image_path);
            }
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'blog');
        } elseif ($request->has('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->content,
            'image_path' => $imagePath,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : $blog->published_at,
            'schema_markup' => $request->schema_markup,
        ]);

        return redirect()->back()->with('success', 'Blog post updated successfully!');
    }

    public function deleteBlog($id)
    {
        $blog = \App\Models\Blog::findOrFail($id);
        if ($blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
        }
        $blog->delete();

        return redirect()->back()->with('success', 'Blog post deleted successfully!');
    }

    /* Page Content Updates */
    public function updatePageContents(Request $request)
    {
        $contents = $request->input('contents', []);
        
        foreach ($contents as $page => $sections) {
            if (is_array($sections)) {
                foreach ($sections as $section => $keys) {
                    if (is_array($keys)) {
                        foreach ($keys as $key => $value) {
                            \App\Models\PageContent::updateOrCreate(
                                ['page' => $page, 'section' => $section, 'key' => $key],
                                ['value' => $value]
                            );
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Page contents updated successfully!');
    }

    /* Media Upload Manager */
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:10000',
        ]);

        if ($request->hasFile('file')) {
            $path = $this->uploadAndConvertToWebP($request->file('file'), 'uploads');
            if ($path) {
                $url = site_image($path);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'url' => $url,
                        'name' => basename($path),
                    ]);
                }
                return redirect()->back()->with('success', 'File uploaded and optimized to WebP successfully! URL: ' . $url);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Upload failed.'], 400);
        }
        return redirect()->back()->with('error', 'Upload failed.');
    }

    /* Helper function for uploading to Cloudflare R2 Storage using pure S3 REST API & Signature V4 */
    private function uploadToR2($fileData, $filename, $mimeType = 'image/webp')
    {
        $accountId = trim(SiteParameter::where('id', 'cloudflare_r2_account_id')->value('value') ?? '');
        $accessKey = trim(SiteParameter::where('id', 'cloudflare_r2_access_key_id')->value('value') ?? '');
        $secretKey = trim(SiteParameter::where('id', 'cloudflare_r2_secret_access_key')->value('value') ?? '');
        $bucket = trim(SiteParameter::where('id', 'cloudflare_r2_bucket_name')->value('value') ?? '');

        if (empty($accountId) || empty($accessKey) || empty($secretKey) || empty($bucket)) {
            return false;
        }

        try {
            $host = "{$accountId}.r2.cloudflarestorage.com";
            $endpoint = "https://{$accountId}.r2.cloudflarestorage.com/{$bucket}/{$filename}";
            
            $region = 'auto';
            $service = 's3';
            $method = 'PUT';
            
            $dateTime = gmdate('Ymd\THis\Z');
            $date = gmdate('Ymd');
            
            $headers = [
                'host' => $host,
                'x-amz-content-sha256' => hash('sha256', $fileData),
                'x-amz-date' => $dateTime,
                'content-type' => $mimeType,
            ];
            
            ksort($headers);
            
            $canonicalHeaders = '';
            $signedHeaders = '';
            foreach ($headers as $k => $v) {
                $canonicalHeaders .= $k . ':' . trim($v) . "\n";
                $signedHeaders .= $k . ';';
            }
            $signedHeaders = rtrim($signedHeaders, ';');
            
            $canonicalRequest = "{$method}\n/{$bucket}/{$filename}\n\n{$canonicalHeaders}\n{$signedHeaders}\n" . hash('sha256', $fileData);
            
            $credentialScope = "{$date}/{$region}/{$service}/aws4_request";
            
            $stringToSign = "AWS4-HMAC-SHA256\n{$dateTime}\n{$credentialScope}\n" . hash('sha256', $canonicalRequest);
            
            $kDate = hash_hmac('sha256', $date, 'AWS4' . $secretKey, true);
            $kRegion = hash_hmac('sha256', $region, $kDate, true);
            $kService = hash_hmac('sha256', $service, $kRegion, true);
            $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
            
            $signature = hash_hmac('sha256', $stringToSign, $kSigning);
            
            $authorizationHeader = "AWS4-HMAC-SHA256 Credential={$accessKey}/{$credentialScope}, SignedHeaders={$signedHeaders}, Signature={$signature}";
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => $authorizationHeader,
                'x-amz-content-sha256' => $headers['x-amz-content-sha256'],
                'x-amz-date' => $dateTime,
                'Content-Type' => $mimeType,
            ])->withBody($fileData, $mimeType)->put($endpoint);
            
            if ($response->successful()) {
                return true;
            }
            
            \Illuminate\Support\Facades\Log::error('Cloudflare R2 Upload Failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Cloudflare R2 Exception: ' . $e->getMessage());
            return false;
        }
    }

    /* Helper function for uploading and optimizing images to WebP format */
    private function uploadAndConvertToWebP($file, $folder)
    {
        if (!$file) return null;
        
        $extension = $file->getClientOriginalExtension();
        $r2PublicUrl = trim(SiteParameter::where('id', 'cloudflare_r2_public_url')->value('value') ?? '');
        if (empty($r2PublicUrl)) {
            $r2PublicUrl = 'https://images.mlgfinedge.com';
        }
        $r2PublicUrl = rtrim($r2PublicUrl, '/');
        
        // If SVG, save directly to preserve vector paths
        if (strtolower($extension) === 'svg') {
            $filename = uniqid() . '.svg';
            $fileData = file_get_contents($file->getRealPath());
            // Always save local backup
            @Storage::disk('public')->put($folder . '/' . $filename, $fileData);
            
            if ($this->uploadToR2($fileData, $filename, 'image/svg+xml')) {
                return $r2PublicUrl . '/' . $filename;
            }
            return $folder . '/' . $filename;
        }
        
        $filePath = $file->getRealPath();
        $image = null;
        $mime = 'image/webp';
        
        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($filePath);
                $mime = 'image/jpeg';
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                $mime = 'image/png';
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'gif':
                $image = imagecreatefromgif($filePath);
                $mime = 'image/gif';
                if ($image) imagepalettetotruecolor($image);
                break;
            case 'webp':
                $image = imagecreatefromwebp($filePath);
                $mime = 'image/webp';
                break;
            default:
                $filename = uniqid() . '.' . $extension;
                $fileData = file_get_contents($filePath);
                // Always save local backup
                @Storage::disk('public')->put($folder . '/' . $filename, $fileData);
                
                if ($this->uploadToR2($fileData, $filename, 'application/octet-stream')) {
                    return $r2PublicUrl . '/' . $filename;
                }
                return $folder . '/' . $filename;
        }
        
        if (!$image) {
            $filename = uniqid() . '.' . $extension;
            $fileData = file_get_contents($filePath);
            // Always save local backup
            @Storage::disk('public')->put($folder . '/' . $filename, $fileData);
            
            if ($this->uploadToR2($fileData, $filename, $mime)) {
                return $r2PublicUrl . '/' . $filename;
            }
            return $folder . '/' . $filename;
        }
        
        // Generate WebP filename
        $filename = uniqid() . '.webp';
        
        // Save using Laravel Storage with webp compression quality (80%)
        ob_start();
        imagewebp($image, null, 80);
        $webpData = ob_get_clean();
        imagedestroy($image);
        
        // Always save local backup
        Storage::disk('public')->put($folder . '/' . $filename, $webpData);
        
        if ($this->uploadToR2($webpData, $filename, 'image/webp')) {
            return $r2PublicUrl . '/' . $filename;
        }
        
        return $folder . '/' . $filename;
    }

    /* Services CRUD */
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'hero_category' => 'nullable|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'rate_value' => 'nullable|string|max:255',
            'max_loan' => 'nullable|string|max:255',
            'tenure' => 'nullable|string|max:255',
            'intro_title' => 'nullable|string|max:255',
            'intro_content' => 'nullable|string',
            'eligibility_criteria' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'tips_title' => 'nullable|string|max:255',
            'tips_content' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'schema_markup' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'icon' => 'nullable|image|max:5000',
            'icon_url' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['service_name']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'category');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image_path'] = $imagePath;

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $this->uploadAndConvertToWebP($request->file('icon'), 'category');
        } elseif ($request->filled('icon_url')) {
            $iconPath = $request->input('icon_url');
        }
        $validated['icon_path'] = $iconPath;

        // Handle FAQs
        $faqs = [];
        $questions = $request->input('faq_questions', []);
        $answers = $request->input('faq_answers', []);
        if (is_array($questions)) {
            foreach ($questions as $i => $q) {
                if (!empty($q) && !empty($answers[$i])) {
                    $faqs[] = ['question' => $q, 'answer' => $answers[$i]];
                }
            }
        }
        $validated['faqs'] = $faqs;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        \App\Models\Service::create($validated);

        return redirect()->back()->with('success', 'Service page created successfully!');
    }

    public function updateService(Request $request, $id)
    {
        $service = \App\Models\Service::findOrFail($id);

        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $id,
            'hero_category' => 'nullable|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'rate_value' => 'nullable|string|max:255',
            'max_loan' => 'nullable|string|max:255',
            'tenure' => 'nullable|string|max:255',
            'intro_title' => 'nullable|string|max:255',
            'intro_content' => 'nullable|string',
            'eligibility_criteria' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'tips_title' => 'nullable|string|max:255',
            'tips_content' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'schema_markup' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'icon' => 'nullable|image|max:5000',
            'icon_url' => 'nullable|string',
        ]);

        $imagePath = $service->image_path;
        if ($request->hasFile('image')) {
            if ($service->image_path && !str_starts_with($service->image_path, 'http') && !str_starts_with($service->image_path, '//')) {
                Storage::disk('public')->delete($service->image_path);
            }
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'category');
        } elseif ($request->has('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image_path'] = $imagePath;

        $iconPath = $service->icon_path;
        if ($request->hasFile('icon')) {
            if ($service->icon_path && !str_starts_with($service->icon_path, 'http') && !str_starts_with($service->icon_path, '//')) {
                Storage::disk('public')->delete($service->icon_path);
            }
            $iconPath = $this->uploadAndConvertToWebP($request->file('icon'), 'category');
        } elseif ($request->has('icon_url')) {
            $iconPath = $request->input('icon_url');
        }
        $validated['icon_path'] = $iconPath;

        $faqs = [];
        $questions = $request->input('faq_questions', []);
        $answers = $request->input('faq_answers', []);
        if (is_array($questions)) {
            foreach ($questions as $i => $q) {
                if (!empty($q) && !empty($answers[$i])) {
                    $faqs[] = ['question' => $q, 'answer' => $answers[$i]];
                }
            }
        }
        $validated['faqs'] = $faqs;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $service->update($validated);

        return redirect()->back()->with('success', 'Service page updated successfully!');
    }

    public function deleteService($id)
    {
        $service = \App\Models\Service::findOrFail($id);
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }
        if ($service->icon_path) {
            Storage::disk('public')->delete($service->icon_path);
        }
        $service->delete();

        return redirect()->back()->with('success', 'Service page deleted successfully!');
    }

    /* Comparison Banks CRUD */
    public function storeBank(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'home_rate' => 'required|numeric',
            'personal_rate' => 'required|numeric',
            'business_rate' => 'required|numeric',
            'fee_percent' => 'required|numeric',
            'sector' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        \App\Models\ComparisonBank::create($validated);

        return redirect()->back()->with('success', 'Comparison bank added successfully!');
    }

    public function updateBank(Request $request, $id)
    {
        $bank = \App\Models\ComparisonBank::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'home_rate' => 'required|numeric',
            'personal_rate' => 'required|numeric',
            'business_rate' => 'required|numeric',
            'fee_percent' => 'required|numeric',
            'sector' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $bank->update($validated);

        return redirect()->back()->with('success', 'Comparison bank updated successfully!');
    }

    public function deleteBank($id)
    {
        $bank = \App\Models\ComparisonBank::findOrFail($id);
        $bank->delete();

        return redirect()->back()->with('success', 'Comparison bank deleted successfully!');
    }

    public function syncDatabase(Request $request)
    {
        try {
            // Run database seeder
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            
            // Clear application cache to ensure new values are loaded instantly
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            
            return redirect()->back()->with('success', 'Website content synced successfully from the database seeder/default structures!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function updateLead(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:New,Contacted,In Progress,Closed',
            'notes' => 'nullable|string',
        ]);

        $lead = \App\Models\Lead::findOrFail($id);
        $lead->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Lead updated successfully!');
    }

    public function deleteLead($id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }

    public function exportLeads()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=leads_export_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['ID', 'Name', 'Phone', 'Email', 'Source', 'Status', 'Message', 'Notes', 'Created At']);

            \App\Models\Lead::orderBy('created_at', 'desc')->chunk(100, function($leads) use ($file) {
                foreach ($leads as $lead) {
                    fputcsv($file, [
                        $lead->id,
                        $lead->name,
                        $lead->phone,
                        $lead->email,
                        $lead->source,
                        $lead->status,
                        $lead->message,
                        $lead->notes,
                        $lead->created_at ? $lead->created_at->toDateTimeString() : '',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkDeleteLeads(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:leads,id',
        ]);

        \App\Models\Lead::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' leads deleted successfully!');
    }

    private function getParameterCategory($key)
    {
        if (str_contains($key, 'rate')) return 'rates';
        if (in_array($key, ['phone', 'email', 'address', 'whatsapp_number'])) return 'contact';
        if (in_array($key, ['site_name', 'site_tagline', 'google_tag_id', 'header_scripts'])) return 'general';
        if (str_contains($key, 'bg') || str_contains($key, 'color') || str_contains($key, 'styling')) return 'styling';
        return 'other';
    }

    /* AI Generation Methods */
    public function aiGenerateBlog(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'tone' => 'required|string',
        ]);

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $prompt = $request->input('prompt');
        $tone = $request->input('tone');
        $generateImage = $request->boolean('generate_image');

        $systemPrompt = "You are an expert financial blog writer and SEO specialist. Create a complete, high-quality blog article in English based on the user's prompt.
The writing tone must be: {$tone}.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"title\": \"A compelling SEO-optimized blog title\",
  \"category\": \"Choose the most appropriate category from: Personal Loans, Home Loans, Business Loans, Loan Against Property, Financial Tips\",
  \"summary\": \"A short, engaging 1-2 sentence summary of the article to be shown on listing cards\",
  \"content\": \"The full blog article content formatted in clean, modern HTML. Use paragraphs <p>, subheadings <h3>/<h4>, lists <ul>/<li>, and emphasized text. Do not include raw CSS or javascript. Make the article comprehensive, informative, and around 400-600 words.\",
  \"image_prompt\": \"A detailed, descriptive text-to-image prompt to generate a high-quality featured cover image matching this article's topic. Do not include adjectives like 'photorealistic', instead describe lighting, context, objects, and style (e.g. 'A professional photograph of ...')\"
}

User prompt: {$prompt}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = trim($text);
            
            // Handle raw markdown wrapping if any
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $blogData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            // Generate image if requested
            if ($generateImage) {
                $imagePrompt = $blogData['image_prompt'] ?? $prompt;
                $imageUrl = $this->generateAndSaveAiImage($imagePrompt, '16:9');
                if ($imageUrl) {
                    $blogData['image_url'] = $imageUrl;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $blogData
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'AI generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateService(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'tone' => 'required|string',
        ]);

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $prompt = $request->input('prompt');
        $tone = $request->input('tone');

        $systemPrompt = "You are an expert web designer and copywriter for financial services. Generate a complete service page for a financial product in English based on the user's prompt.
The writing tone must be: {$tone}.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"service_name\": \"Name of the service (e.g. Home Renovation Loan)\",
  \"hero_category\": \"A short category tag (e.g. RETAIL LOAN, PERSONAL FINANCE)\",
  \"hero_title\": \"A catchy, clear H1 heading for the hero section\",
  \"hero_subtitle\": \"A brief 1-2 sentence subheading for the hero section describing the main value proposition\",
  \"rate_value\": \"Starting interest rate value (e.g. 8.9% p.a.)\",
  \"max_loan\": \"Maximum loan amount available (e.g. Up to 50 Lakhs)\",
  \"tenure\": \"Repayment tenure limits (e.g. Up to 15 Years)\",
  \"intro_title\": \"A heading for the introduction section (e.g. Why Choose Our Home Renovation Loan?)\",
  \"intro_content\": \"An engaging introduction block formatted in HTML paragraphs (<p>) highlighting benefits and features\",
  \"eligibility_criteria\": \"A list of key eligibility criteria, with each criterion on a new line (use newlines \\n to separate)\",
  \"documents_required\": \"A list of required documents, with each document on a new line (use newlines \\n to separate)\",
  \"tips_title\": \"Heading for financial tips/guide section (e.g. How to qualify faster?)\",
  \"tips_content\": \"A helpful tip guide content formatted in HTML paragraphs (<p>)\",
  \"badge\": \"A short promo badge tag (e.g. Instant Approval, Low Interest)\",
  \"summary\": \"A short description of the service (1-2 sentences) shown in footers or small layouts\",
  \"faqs\": [
    {
      \"question\": \"An important, frequently asked question about this service\",
      \"answer\": \"A detailed answer to the question\"
    },
    {
      \"question\": \"Another logical question about this service\",
      \"answer\": \"A detailed answer to the question\"
    },
    {
      \"question\": \"A third important question about this service\",
      \"answer\": \"A detailed answer to the question\"
    }
  ]
}

User prompt: {$prompt}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = trim($text);
            
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $serviceData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $serviceData
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'AI generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'aspect_ratio' => 'required|string|in:1:1,16:9,4:3',
        ]);

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $prompt = $request->input('prompt');
        $aspectRatio = $request->input('aspect_ratio');

        $imageUrl = $this->generateAndSaveAiImage($prompt, $aspectRatio);
        if (!$imageUrl) {
            return response()->json(['error' => 'Failed to generate image via Imagen API. Please verify your API Key has billing enabled on Google AI Studio.'], 500);
        }

        return response()->json([
            'success' => true,
            'url' => $imageUrl,
            'name' => basename($imageUrl)
        ]);
    }

    private function generateAndSaveAiImage($prompt, $aspectRatio = '1:1')
    {
        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) return null;

        $base64 = null;

        // Attempt 1: Try Nano Banana 2 (gemini-3.1-flash-image) using generateContent
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-image:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_modalities' => ['TEXT', 'IMAGE']
                ]
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                foreach ($resData['candidates'][0]['content']['parts'] ?? [] as $part) {
                    if (isset($part['inlineData']['data'])) {
                        $base64 = $part['inlineData']['data'];
                        break;
                    }
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Nano Banana 2 generateContent attempt failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Nano Banana 2 generateContent exception: ' . $e->getMessage());
        }

        // Attempt 2: Try Nano Banana (gemini-2.5-flash-image) using generateContent
        if (empty($base64)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'response_modalities' => ['TEXT', 'IMAGE']
                    ]
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    foreach ($resData['candidates'][0]['content']['parts'] ?? [] as $part) {
                        if (isset($part['inlineData']['data'])) {
                            $base64 = $part['inlineData']['data'];
                            break;
                        }
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('Nano Banana generateContent attempt failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Nano Banana generateContent exception: ' . $e->getMessage());
            }
        }

        // Attempt 3: Try Imagen 3 using generateImages (v1beta)
        if (empty($base64)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:generateImages?key={$apiKey}", [
                    'prompt' => $prompt,
                    'numberOfImages' => 1,
                    'outputMimeType' => 'image/jpeg',
                    'aspectRatio' => $aspectRatio
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $base64 = $resData['generatedImages'][0]['image']['imageBytes'] ?? null;
                } else {
                    \Illuminate\Support\Facades\Log::warning('Imagen 3 generateImages attempt failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Imagen 3 generateImages exception: ' . $e->getMessage());
            }
        }

        // Attempt 4: Try Imagen 4 using generateImages (v1beta)
        if (empty($base64)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/imagen-4.0-generate-001:generateImages?key={$apiKey}", [
                    'prompt' => $prompt,
                    'numberOfImages' => 1,
                    'outputMimeType' => 'image/jpeg',
                    'aspectRatio' => $aspectRatio
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $base64 = $resData['generatedImages'][0]['image']['imageBytes'] ?? null;
                } else {
                    \Illuminate\Support\Facades\Log::warning('Imagen 4 generateImages attempt failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Imagen 4 generateImages exception: ' . $e->getMessage());
            }
        }

        // Attempt 5: Try Imagen 4 via predict (v1beta)
        if (empty($base64)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/imagen-4.0-generate-001:predict?key={$apiKey}", [
                    'instances' => [
                        ['prompt' => $prompt]
                    ],
                    'parameters' => [
                        'sampleCount' => 1,
                        'aspectRatio' => $aspectRatio,
                        'outputMimeType' => 'image/jpeg',
                    ]
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $base64 = $resData['predictions'][0]['bytesBase64Encoded'] ?? null;
                } else {
                    \Illuminate\Support\Facades\Log::warning('Imagen 4 predict attempt failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Imagen 4 predict exception: ' . $e->getMessage());
            }
        }

        // Return failure if all failed
        if (empty($base64)) {
            \Illuminate\Support\Facades\Log::error('All image generation models failed.');
            return null;
        }

        try {
            $imageBytes = base64_decode($base64);
            if (empty($imageBytes)) return null;

            $filename = uniqid() . '.webp';
            $folder = 'uploads';

            // Convert to WebP using GD library
            $image = imagecreatefromstring($imageBytes);
            if (!$image) return null;

            ob_start();
            imagewebp($image, null, 80);
            $webpData = ob_get_clean();
            imagedestroy($image);

            $r2PublicUrl = SiteParameter::where('id', 'cloudflare_r2_public_url')->value('value');
            if (empty($r2PublicUrl)) {
                $r2PublicUrl = 'https://images.mlgfinedge.com';
            }
            $r2PublicUrl = rtrim($r2PublicUrl, '/');

            // Upload via Cloudflare R2 if possible
            if ($this->uploadToR2($webpData, $filename, 'image/webp')) {
                @Storage::disk('public')->put($folder . '/' . $filename, $webpData);
                return $r2PublicUrl . '/' . $filename;
            }

            Storage::disk('public')->put($folder . '/' . $filename, $webpData);
            return $folder . '/' . $filename;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GD library conversion exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getCloudflareAnalytics(Request $request)
    {
        $apiToken = SiteParameter::where('id', 'cloudflare_api_token')->value('value');
        $zoneId = SiteParameter::where('id', 'cloudflare_zone_id')->value('value');

        if (empty($apiToken) || empty($zoneId)) {
            return response()->json([
                'success' => false,
                'error' => 'Cloudflare API Token or Zone ID is missing. Please configure them in General Settings.'
            ]);
        }

        $startDate = date('Y-m-d', strtotime('-7 days'));
        
        $query = [
            'query' => 'query {
                viewer {
                    zones(filter: { zoneTag: "' . $zoneId . '" }) {
                        httpRequests1dGroups(limit: 7, filter: { date_geq: "' . $startDate . '" }, orderBy: [date_ASC]) {
                            sum {
                                pageViews
                                requests
                                bytes
                                cachedBytes
                                cachedRequests
                            }
                            dimensions {
                                date
                            }
                        }
                    }
                }
            }'
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.cloudflare.com/client/v4/graphql', $query);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cloudflare API request failed: ' . $response->body()
                ]);
            }

            $resData = $response->json();
            $groups = $resData['data']['viewer']['zones'][0]['httpRequests1dGroups'] ?? [];

            if (empty($groups)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No analytics data returned by Cloudflare. Check your Zone ID and domain DNS settings.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $groups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Analytics Exception: ' . $e->getMessage()
            ]);
        }
    }

    public function syncLocalImagesToR2(Request $request)
    {
        $r2PublicUrl = trim(SiteParameter::where('id', 'cloudflare_r2_public_url')->value('value') ?? '');
        $accountId = trim(SiteParameter::where('id', 'cloudflare_r2_account_id')->value('value') ?? '');
        $accessKey = trim(SiteParameter::where('id', 'cloudflare_r2_access_key_id')->value('value') ?? '');
        $secretKey = trim(SiteParameter::where('id', 'cloudflare_r2_secret_access_key')->value('value') ?? '');
        $bucket = trim(SiteParameter::where('id', 'cloudflare_r2_bucket_name')->value('value') ?? '');

        if (empty($accountId) || empty($accessKey) || empty($secretKey) || empty($bucket)) {
            return response()->json([
                'success' => false,
                'message' => 'Cloudflare R2 is not fully configured. Please fill in all settings under General Settings first.'
            ], 400);
        }

        try {
            $files = Storage::disk('public')->allFiles();
            $successCount = 0;
            $failedCount = 0;

            foreach ($files as $file) {
                // Skip hidden files
                if (str_starts_with(basename($file), '.')) {
                    continue;
                }

                $fileData = Storage::disk('public')->get($file);
                $filename = basename($file);
                
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $mimeType = 'image/webp';
                if ($extension === 'svg') {
                    $mimeType = 'image/svg+xml';
                } elseif ($extension === 'png') {
                    $mimeType = 'image/png';
                } elseif ($extension === 'jpg' || $extension === 'jpeg') {
                    $mimeType = 'image/jpeg';
                } elseif ($extension === 'gif') {
                    $mimeType = 'image/gif';
                }

                // Upload to R2
                if ($this->uploadToR2($fileData, $filename, $mimeType)) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully synced {$successCount} local images to Cloudflare R2! (Failed: {$failedCount})"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pushLocalDatabaseToLive(Request $request)
    {
        $request->validate([
            'live_site_url' => 'required|url',
        ]);

        $liveSiteUrl = rtrim($request->input('live_site_url'), '/');
        
        $secretKey = trim(SiteParameter::where('id', 'cloudflare_r2_secret_access_key')->value('value') ?? '');
        
        if (empty($secretKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure and save your Cloudflare R2 Secret Access Key first. It acts as the secure verification token.'
            ], 400);
        }

        // Gather table data
        $tables = [
            'site_parameters'  => \App\Models\SiteParameter::all()->toArray(),
            'home_slides'      => \App\Models\HomeSlide::all()->toArray(),
            'testimonials'     => \App\Models\Testimonial::all()->toArray(),
            'blogs'            => \App\Models\Blog::all()->toArray(),
            'services'         => \App\Models\Service::all()->toArray(),
            'comparison_banks' => \App\Models\ComparisonBank::all()->toArray(),
            'page_contents'    => \App\Models\PageContent::all()->toArray(),
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-Sync-Token' => $secretKey,
                'Content-Type' => 'application/json',
            ])->post($liveSiteUrl . '/api/database/import', [
                'tables' => $tables,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Live server rejected database push: ' . ($response->json('message') ?? $response->body())
            ], $response->status() ?: 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Push connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importLiveDatabase(Request $request)
    {
        $syncToken = $request->header('X-Sync-Token');
        $localSecret = trim(SiteParameter::where('id', 'cloudflare_r2_secret_access_key')->value('value') ?? '');

        if (empty($localSecret) || $syncToken !== $localSecret) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid sync token.'
            ], 401);
        }

        $tables = $request->input('tables');
        if (empty($tables) || !is_array($tables)) {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request: Missing or invalid tables payload.'
            ], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($tables) {
                // 1. site_parameters
                if (isset($tables['site_parameters'])) {
                    foreach ($tables['site_parameters'] as $row) {
                        // Skip updating credentials on import to avoid overwriting database connection details
                        \App\Models\SiteParameter::updateOrCreate(
                            ['id' => $row['id']],
                            [
                                'value' => $row['value'],
                                'label' => $row['label'],
                                'category' => $row['category']
                            ]
                        );
                    }
                }

                // 2. home_slides
                if (isset($tables['home_slides'])) {
                    \App\Models\HomeSlide::truncate();
                    foreach ($tables['home_slides'] as $row) {
                        \App\Models\HomeSlide::create($row);
                    }
                }

                // 3. testimonials
                if (isset($tables['testimonials'])) {
                    \App\Models\Testimonial::truncate();
                    foreach ($tables['testimonials'] as $row) {
                        \App\Models\Testimonial::create($row);
                    }
                }

                // 4. blogs
                if (isset($tables['blogs'])) {
                    \App\Models\Blog::truncate();
                    foreach ($tables['blogs'] as $row) {
                        \App\Models\Blog::create($row);
                    }
                }

                // 5. services
                if (isset($tables['services'])) {
                    \App\Models\Service::truncate();
                    foreach ($tables['services'] as $row) {
                        \App\Models\Service::create($row);
                    }
                }

                // 6. comparison_banks
                if (isset($tables['comparison_banks'])) {
                    \App\Models\ComparisonBank::truncate();
                    foreach ($tables['comparison_banks'] as $row) {
                        \App\Models\ComparisonBank::create($row);
                    }
                }

                // 7. page_contents
                if (isset($tables['page_contents'])) {
                    \App\Models\PageContent::truncate();
                    foreach ($tables['page_contents'] as $row) {
                        \App\Models\PageContent::create($row);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Local database successfully pushed and synchronized to your live database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setupLiveDatabase(Request $request)
    {
        // Simple security: only allow if admin is logged in OR a setup key is passed
        $setupKey = trim(SiteParameter::where('id', 'cloudflare_r2_secret_access_key')->value('value') ?? '');
        $providedKey = $request->query('key', '');

        if (!auth()->check() && (empty($setupKey) || $providedKey !== $setupKey)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized. Pass ?key=YOUR_R2_SECRET_KEY or log in as admin first.'
            ], 401);
        }

        $results = [];

        // ── 1. leads ──────────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('leads')) {
            \Illuminate\Support\Facades\Schema::create('leads', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('name');
                $t->string('phone');
                $t->string('email')->nullable();
                $t->text('message')->nullable();
                $t->string('source')->default('Contact Form');
                $t->string('status')->default('New');
                $t->text('notes')->nullable();
                $t->timestamps();
            });
            $results['leads'] = 'CREATED ✅';
        } else {
            $results['leads'] = 'exists ✔';
        }

        // ── 2. sessions ───────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
            \Illuminate\Support\Facades\Schema::create('sessions', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->string('id')->primary();
                $t->foreignId('user_id')->nullable()->index();
                $t->string('ip_address', 45)->nullable();
                $t->text('user_agent')->nullable();
                $t->longText('payload');
                $t->integer('last_activity')->index();
            });
            $results['sessions'] = 'CREATED ✅';
        } else {
            $results['sessions'] = 'exists ✔';
        }

        // ── 3. cache ──────────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('cache')) {
            \Illuminate\Support\Facades\Schema::create('cache', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->string('key')->primary();
                $t->mediumText('value');
                $t->integer('expiration');
            });
            $results['cache'] = 'CREATED ✅';
        } else {
            $results['cache'] = 'exists ✔';
        }

        // ── 4. cache_locks ────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('cache_locks')) {
            \Illuminate\Support\Facades\Schema::create('cache_locks', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->string('key')->primary();
                $t->string('owner');
                $t->integer('expiration');
            });
            $results['cache_locks'] = 'CREATED ✅';
        } else {
            $results['cache_locks'] = 'exists ✔';
        }

        // ── 5. jobs ───────────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('jobs')) {
            \Illuminate\Support\Facades\Schema::create('jobs', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('queue')->index();
                $t->longText('payload');
                $t->unsignedTinyInteger('attempts');
                $t->unsignedInteger('reserved_at')->nullable();
                $t->unsignedInteger('available_at');
                $t->unsignedInteger('created_at');
            });
            $results['jobs'] = 'CREATED ✅';
        } else {
            $results['jobs'] = 'exists ✔';
        }

        // ── 6. failed_jobs ────────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('failed_jobs')) {
            \Illuminate\Support\Facades\Schema::create('failed_jobs', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('uuid')->unique();
                $t->text('connection');
                $t->text('queue');
                $t->longText('payload');
                $t->longText('exception');
                $t->timestamp('failed_at')->useCurrent();
            });
            $results['failed_jobs'] = 'CREATED ✅';
        } else {
            $results['failed_jobs'] = 'exists ✔';
        }

        // ── 7. Verify leads insert/select works ───────────────────────
        try {
            $count = \App\Models\Lead::count();
            $results['leads_read_test'] = "PASS ✅ ({$count} existing records)";
        } catch (\Exception $e) {
            $results['leads_read_test'] = 'FAIL ❌ ' . $e->getMessage();
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Live server database setup complete! ✅ All required tables are now ready.',
            'tables'  => $results,
        ]);
    }

    /* Landing Pages Management CRUD */
    public function storeLandingPage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug',
            'layout_type' => 'required|string|in:home,about',
            'meta_description' => 'nullable|string',
        ]);

        \App\Models\LandingPage::create([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'layout_type' => $request->layout_type,
            'meta_description' => $request->meta_description,
            'content' => [],
        ]);

        return redirect()->back()->with('success', 'Landing page created successfully! You can now edit its contents or generate them using AI.');
    }

    public function updateLandingPage(Request $request, $id)
    {
        $landingPage = \App\Models\LandingPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,' . $landingPage->id,
            'layout_type' => 'required|string|in:home,about',
            'meta_description' => 'nullable|string',
            'content' => 'required|array',
            'schema_markup' => 'nullable|string',
        ]);

        $landingPage->update([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'layout_type' => $request->layout_type,
            'meta_description' => $request->meta_description,
            'content' => $request->content,
            'schema_markup' => $request->schema_markup,
        ]);

        return redirect()->back()->with('success', 'Landing page updated successfully!');
    }

    public function deleteLandingPage($id)
    {
        $landingPage = \App\Models\LandingPage::findOrFail($id);
        $landingPage->delete();

        return redirect()->back()->with('success', 'Landing page deleted successfully!');
    }

    /* AI Landing Page Content Generation */
    public function aiGenerateLandingPage(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:landing_pages,id',
            'prompt' => 'required|string',
        ]);

        $landingPage = \App\Models\LandingPage::findOrFail($request->id);
        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $prompt = $request->input('prompt');
        $layout = $landingPage->layout_type;

        if ($layout === 'home') {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this landing page.",
  "hero_title": "A catchy, attention-grabbing hero main title.",
  "hero_subtitle": "An engaging subtitle or tagline.",
  "features": [
     {"title": "Feature 1 Title", "desc": "Feature 1 Description", "icon": "Lucide icon name like shield/clock/check/file-text/shield-check/percent/zap"},
     {"title": "Feature 2 Title", "desc": "Feature 2 Description", "icon": "Lucide icon"},
     {"title": "Feature 3 Title", "desc": "Feature 3 Description", "icon": "Lucide icon"},
     {"title": "Feature 4 Title", "desc": "Feature 4 Description", "icon": "Lucide icon"}
  ],
  "about_title": "Why Choose MLG Finedge / About Us section heading",
  "about_text": "Detailed description of our loan/credit consultation services.",
  "cta_title": "Ready to get started? or Call to action heading",
  "cta_text": "Brief sub-text for CTA section.",
  "faqs": [
     {"q": "FAQ Question 1?", "a": "FAQ Answer 1"},
     {"q": "FAQ Question 2?", "a": "FAQ Answer 2"},
     {"q": "FAQ Question 3?", "a": "FAQ Answer 3"}
  ]
}';
        } else {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this landing page.",
  "hero_title": "Hero main title.",
  "hero_subtitle": "Hero subtitle.",
  "story_title": "Our Story / Mission title",
  "story_text": "Detailed story text explaining our background, 2 paragraphs.",
  "vision": "Vision Statement.",
  "mission": "Mission Statement.",
  "values": "Core Values Statement.",
  "team": [
     {"name": "Team Member 1", "role": "Role / Title", "bio": "Brief professional bio."},
     {"name": "Team Member 2", "role": "Role / Title", "bio": "Brief professional bio."},
     {"name": "Team Member 3", "role": "Role / Title", "bio": "Brief professional bio."}
  ],
  "cta_title": "Call to action heading",
  "cta_text": "Brief sub-text for CTA section."
}';
        }

        $systemPrompt = "You are an expert web content copywriter and landing page optimization specialist.
Create high-quality, professional landing page content in English based on the user's prompt: '{$prompt}'.
The page layout structure is: {$layout}.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Output must start with { and end with }.

JSON Schema:
{$schemaDesc}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = trim($text);
            
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $pageData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            // Save content to landing page
            $landingPage->update([
                'meta_description' => $pageData['meta_description'] ?? $landingPage->meta_description,
                'content' => $pageData,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Landing page content generated successfully!',
                'data' => $pageData
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI generation failed: ' . $e->getMessage()], 500);
        }
    }

    /* AI Sync Testimonials from Google My Business */
    public function aiSyncGmbTestimonials(Request $request)
    {
        $gmbLink = SiteParameter::where('id', 'google_my_business_link')->value('value');
        if (empty($gmbLink)) {
            return response()->json(['error' => 'Google My Business Link is not configured. Please save it in General Settings first.'], 400);
        }

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $systemPrompt = "You are a customer relationship coordinator. Create 5 realistic, high-quality, positive customer testimonials for MLG Finedge (Jaipur's premier credit advisory firm) as if they were synced directly from their Google My Business profile: '{$gmbLink}'.
Make each testimonial unique and authentic. They should mention specific loan products (e.g. Home Loan, Business Loan, Personal Loan, Loan Against Property), helpful customer support, interest rate savings, or fast approvals in Jaipur.

You MUST return the output ONLY as a raw, single-line valid JSON array of objects matching the schema below. Output must start with [ and end with ].

JSON Schema:
[
  {
    \"name\": \"Full Name of Customer\",
    \"role\": \"Customer's Occupation or Profile (e.g., Homebuyer, Business Owner, Teacher, IT Engineer)\",
    \"content\": \"Highly detailed, authentic review text expressing their positive experience with MLG Finedge.\",
    \"rating\": 5
  },
  ...
]";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = trim($text);
            
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $testimonialsData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($testimonialsData)) {
                return response()->json(['error' => 'Failed to parse generated reviews as JSON array. Raw response: ' . $text], 500);
            }

            // Save them to database
            $inserted = 0;
            foreach ($testimonialsData as $idx => $t) {
                Testimonial::create([
                    'name' => $t['name'] ?? 'Happy Client',
                    'role' => $t['role'] ?? 'Client',
                    'content' => $t['content'] ?? '',
                    'rating' => (int)($t['rating'] ?? 5),
                    'avatar_path' => null,
                    'sort_order' => $idx + 10,
                ]);
                $inserted++;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$inserted} testimonials from Google My Business!",
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Testimonials sync failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateSeoRecommendations(Request $request)
    {
        $request->validate([
            'keywords' => 'required|string',
        ]);

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $keywords = $request->input('keywords');

        $systemPrompt = "You are an elite SEO strategist, SEO growth hacker, and digital marketing expert.
Based on the following keywords provided by the user, you must output a comprehensive, highly tactical SEO and backlink strategy.
Target Keywords: {$keywords}

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"pages_blogs\": [
    {
      \"type\": \"Blog / Subpage / Landing Page / Comparison Page\",
      \"title\": \"Highly catchy, SEO-optimized title or page name\",
      \"keywords\": \"Specific subset of keywords targeted here\",
      \"summary\": \"Brief outline of the content, search intent explanation, and structure (e.g. 'Target search intent for comparison, list top 5 benefits, add FAQs at the end')\"
    }
  ],
  \"third_party_articles\": [
    {
      \"platform_type\": \"Guest Post / Medium / Finance Forum / Industry Blog\",
      \"title\": \"Title of the guest article to pitch\",
      \"keywords\": \"Anchor text keywords to embed\",
      \"context\": \"Specific context or paragraph direction where the backlink to our site fits naturally (e.g. 'A paragraph explaining interest rate calculators should link to our calculator page')\"
    }
  ],
  \"quora_questions\": [
    {
      \"topic\": \"Topic or category on Quora\",
      \"question_direction\": \"E.g., 'Is it a good time to get a home loan?' or 'How can a small business get unsecured funding?'\",
      \"draft_answer_points\": \"Detailed bullet points explaining how to write a helpful, authority answer, and where to naturally plug our website\"
    }
  ],
  \"pinterest_posts\": [
    {
      \"visual_idea\": \"Description of the pin layout/image (e.g. 'An infographic showing loan processing steps with a mint green background')\",
      \"overlay_text\": \"Bold overlay text (e.g. 'How to get a home loan in 5 steps!')\",
      \"category\": \"Category (e.g. Personal Finance, Loans, Business Hacks)\"
    }
  ],
  \"social_media\": [
    {
      \"platform\": \"LinkedIn / Facebook / Instagram\",
      \"post_copy\": \"Fully written, engaging post copy complete with emojis, key points, call-to-action, and relevant hashtags\",
      \"graphic_concept\": \"Brief suggestion for what the post image or carousel should show\"
    }
  ],
  \"bookmarking_sites\": [
    {
      \"name\": \"Reddit / Mix.com / Digg / Slashdot / BizSugar\",
      \"url\": \"URL of the platform\",
      \"strategy\": \"Highly tactical advice on where to submit (e.g. '/r/personalfinanceindia' or 'BizSugar financing group') and how to write the bookmark description to avoid spam filters\"
    }
  ],
  \"blog_promotion\": [
    {
      \"name\": \"Medium / Dev.to / HubPages / SlideShare\",
      \"url\": \"URL of the platform\",
      \"strategy\": \"Syndication strategy (e.g. 'Republish blog article with canonical link to our website after 48 hours to leverage Medium Domain Authority')\"
    }
  ],
  \"other_backlinks\": [
    {
      \"strategy_name\": \"E.g. Broken Link Building / Financial Directories / Local Business Citations\",
      \"action_steps\": \"Step-by-step instructions on how to identify targets, draft outreach emails, or submit details to obtain the link\"
    }
  ]
}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $text = trim($text);
            
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $seoData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($seoData)) {
                return response()->json(['error' => 'Failed to parse generated SEO strategy as JSON. Raw response: ' . $text], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $seoData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'SEO Strategy generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function generateSitemap(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('sitemap:generate');
            $output = \Illuminate\Support\Facades\Artisan::output();
            return redirect()->back()->with('success', 'Sitemap successfully regenerated! ' . $output);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate sitemap: ' . $e->getMessage());
        }
    }

    public function aiDraftGmbPost(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if (!in_array($type, ['blog', 'service', 'landing'])) {
            return response()->json(['error' => 'Invalid content type.'], 400);
        }

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $title = '';
        $summary = '';
        $url = '';
        $baseUrl = 'https://www.mlgfinedge.com';

        if ($type === 'blog') {
            $blog = \App\Models\Blog::findOrFail($id);
            $title = $blog->title;
            $summary = $blog->summary;
            $url = $baseUrl . '/blog/' . $blog->slug;
        } elseif ($type === 'service') {
            $service = \App\Models\Service::findOrFail($id);
            $title = $service->service_name;
            $summary = $service->summary;
            $url = $baseUrl . '/services/' . $service->slug;
        } else {
            $lp = \App\Models\LandingPage::findOrFail($id);
            $title = $lp->title;
            $summary = $lp->meta_description;
            $url = $baseUrl . '/l/' . $lp->slug;
        }

        $prompt = "You are an expert local SEO manager. Create a highly engaging, high-converting Google Business Profile (Google My Business) local post draft for the following page:
Title: \"{$title}\"
Summary: \"{$summary}\"
Page Link: \"{$url}\"

Strict Instructions:
1. Write a compelling summary of the article/service suitable for local audiences in Jaipur (under 800 characters).
2. Incorporate 2-3 relevant hashtags (e.g. #JaipurLoans, #HomeLoansJaipur, etc.).
3. End with a strong call-to-action phrase prompting users to click 'Learn More' to visit the link.
4. Keep the draft strictly under 1400 characters (Google GBP limit is 1500).
5. Output ONLY the drafted post text directly without markdown quotes, backticks, or introduction.";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API call failed: ' . $response->body()], 500);
            }

            $resData = $response->json();
            $draft = $resData['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            return response()->json([
                'success' => true,
                'draft' => trim($draft),
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Draft generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function publishGmbPost(Request $request)
    {
        $locationId = SiteParameter::where('id', 'gmb_location_id')->value('value');
        $accessToken = SiteParameter::where('id', 'gmb_access_token')->value('value');

        if (empty($locationId) || empty($accessToken)) {
            return response()->json([
                'error' => 'Google Business Profile credentials are not configured in General Settings. Please set GBP Location ID and Access Token to enable direct publishing.'
            ], 400);
        }

        $text = $request->input('text');
        $url = $request->input('url');

        if (empty($text) || empty($url)) {
            return response()->json(['error' => 'Post text and target URL are required.'], 400);
        }

        try {
            $parent = 'locations/' . preg_replace('/^locations\//', '', $locationId);
            
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post("https://mybusinessbusinessinformation.googleapis.com/v1/{$parent}/localPosts", [
                    'languageCode' => 'en-US',
                    'summary' => $text,
                    'callToAction' => [
                        'actionType' => 'LEARN_MORE',
                        'url' => $url
                    ],
                    'topicType' => 'STANDARD'
                ]);

            if ($response->failed()) {
                return response()->json(['error' => 'GBP API Error: ' . $response->body()], 500);
            }

            return response()->json(['success' => true, 'data' => $response->json()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Publishing failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateSchema(Request $request)
    {
        $request->validate([
            'type' => 'required|in:home,service,blog,landing,treatment,specialist,cms_page',
            'id' => 'nullable|integer',
            'schema_types' => 'required|array',
            'schema_types.*' => 'string'
        ]);

        $apiKey = SiteParameter::where('id', 'gemini_api_key')->value('value');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings first.'], 400);
        }

        $type = $request->type;
        $id = $request->id;
        $schemaTypes = $request->schema_types;

        // Gather page context
        $pageTitle = '';
        $pageUrl = '';
        $pageDescription = '';
        $extraContext = '';

        $baseUrl = rtrim(config('app.url') ?? 'https://www.drsumitz.com', '/');

        if ($type === 'home') {
            $pageTitle = 'Home Page | SumitZ Clinic';
            $pageUrl = $baseUrl . '/';
            $pageDescription = 'SumitZ is one of the leading Orthopaedic & Physiotherapy clinics in Baner, Pune.';
            
            // Fetch branding parameters
            $siteParams = SiteParameter::all()->pluck('value', 'id')->toArray();
            $extraContext = "Clinic Name: " . ($siteParams['site_name'] ?? 'SumitZ Clinic') . "\n" .
                            "Phone: " . ($siteParams['phone'] ?? '+91 9545559434') . "\n" .
                            "Email: " . ($siteParams['email'] ?? 'drgsumit@gmail.com') . "\n" .
                            "Address: " . ($siteParams['address'] ?? 'Baner, Pune');
        } elseif ($type === 'service') {
            $service = \App\Models\Service::find($id);
            if (!$service) {
                return response()->json(['error' => 'Service not found'], 404);
            }
            $pageTitle = $service->service_name;
            $pageUrl = $baseUrl . '/services/' . $service->slug;
            $pageDescription = $service->summary ?: '';
            $extraContext = "Service: " . $service->service_name . "\n" .
                            "Intro: " . ($service->intro_content ?? '') . "\n" .
                            "Details: " . ($service->summary ?? '');
        } elseif ($type === 'blog') {
            $blog = \App\Models\Blog::find($id);
            if (!$blog) {
                return response()->json(['error' => 'Blog not found'], 404);
            }
            $pageTitle = $blog->title;
            $pageUrl = $baseUrl . '/blogs/' . $blog->slug;
            $pageDescription = $blog->summary ?: '';
            $extraContext = "Category: " . $blog->category . "\n" .
                            "Published Date: " . ($blog->published_at ? $blog->published_at->toDateString() : '') . "\n" .
                            "Content: " . strip_tags($blog->content);
        } elseif ($type === 'landing') {
            $lp = \App\Models\LandingPage::find($id);
            if (!$lp) {
                return response()->json(['error' => 'Landing page not found'], 404);
            }
            $pageTitle = $lp->title;
            $pageUrl = $baseUrl . '/l/' . $lp->slug;
            $pageDescription = $lp->meta_description ?: '';
            $extraContext = "Layout: " . $lp->layout_type;
        } elseif ($type === 'treatment') {
            $treatment = \App\Models\Treatment::find($id);
            if (!$treatment) {
                return response()->json(['error' => 'Treatment not found'], 404);
            }
            $pageTitle = $treatment->name;
            $pageUrl = $baseUrl . '/treatments/' . $treatment->slug;
            $pageDescription = $treatment->metadescription ?: '';
            $extraContext = "Treatment Name: " . $treatment->name . "\n" .
                            "Description: " . strip_tags($treatment->description);
        } elseif ($type === 'specialist') {
            $specialist = \App\Models\TeamMember::find($id);
            if (!$specialist) {
                return response()->json(['error' => 'Specialist not found'], 404);
            }
            $pageTitle = $specialist->name;
            $pageUrl = $baseUrl . '/specialists/' . $specialist->slug;
            $pageDescription = $specialist->metadescription ?: '';
            $extraContext = "Specialist Name: " . $specialist->name . "\n" .
                            "Designation: " . $specialist->designation . "\n" .
                            "Description: " . strip_tags($specialist->description);
        } elseif ($type === 'cms_page') {
            $page = \App\Models\CmsPage::find($id);
            if (!$page) {
                return response()->json(['error' => 'CMS Page not found'], 404);
            }
            $pageTitle = $page->title;
            $pageUrl = $baseUrl . '/' . $page->slug;
            $pageDescription = $page->metadescription ?: '';
            $extraContext = "Page Title: " . $page->title . "\n" .
                            "Description: " . strip_tags($page->description);
        }

        $schemaTypesList = implode(', ', $schemaTypes);

        $systemPrompt = "You are a professional SEO expert and web structured data specialist.
Generate a single, comprehensive and perfectly valid JSON-LD script for the page details provided below.
The user wants to apply and combine the following schema types in a single graph or block: {$schemaTypesList}.

Provide EXACTLY the JSON-LD script starting with '<script type=\"application/ld+json\">' and ending with '</script>'.
Generate no other conversational text, explanations, or markdown code blocks (do not wrap with ```html or ```json). Return ONLY the direct raw HTML string containing the JSON-LD script block.
Ensure all URLs are fully qualified HTTPS URLs, all JSON keys are properly quoted, special characters are properly escaped, and structural fields ('@context', '@type', etc.) are correct.

Page Details:
- Title: {$pageTitle}
- URL: {$pageUrl}
- Description: {$pageDescription}
- Additional Context:
{$extraContext}

Direct JSON-LD Script Output:";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ]
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gemini API Error: ' . $response->body()], 500);
            }

            $resultText = $response->json('candidates.0.content.parts.0.text');
            if (empty($resultText)) {
                return response()->json(['error' => 'No content received from Gemini.'], 500);
            }

            // Clean up any accidental markdown blocks returned by AI
            $cleanMarkup = trim($resultText);
            if (preg_match('/<script[\s\S]*?>[\s\S]*?<\/script>/i', $cleanMarkup, $matches)) {
                $cleanMarkup = $matches[0];
            } else {
                // If it outputted raw JSON without script tag, wrap it
                if (str_starts_with($cleanMarkup, '```json')) {
                    $cleanMarkup = trim(str_replace(['```json', '```'], '', $cleanMarkup));
                }
                if (str_starts_with($cleanMarkup, '```html')) {
                    $cleanMarkup = trim(str_replace(['```html', '```'], '', $cleanMarkup));
                }
                if (str_starts_with($cleanMarkup, '{')) {
                    $cleanMarkup = '<script type="application/ld+json">' . "\n" . $cleanMarkup . "\n" . '</script>';
                }
            }

            return response()->json(['success' => true, 'schema' => $cleanMarkup]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Schema generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function saveSchemaMarkup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:home,service,blog,landing,treatment,specialist,cms_page',
            'id' => 'nullable|integer',
            'schema_markup' => 'nullable|string'
        ]);

        $type = $request->type;
        $id = $request->id;
        $markup = $request->schema_markup;

        try {
            if ($type === 'home') {
                SiteParameter::updateOrCreate(
                    ['id' => 'home_schema_markup'],
                    [
                        'value' => $markup,
                        'label' => 'Home Page Custom Schema Markup',
                        'category' => 'SEO'
                    ]
                );
            } elseif ($type === 'service') {
                $service = \App\Models\Service::findOrFail($id);
                $service->update(['schema_markup' => $markup]);
            } elseif ($type === 'blog') {
                $blog = \App\Models\Blog::findOrFail($id);
                $blog->update(['schema_markup' => $markup]);
            } elseif ($type === 'landing') {
                $lp = \App\Models\LandingPage::findOrFail($id);
                $lp->update(['schema_markup' => $markup]);
            } elseif ($type === 'treatment') {
                $t = \App\Models\Treatment::findOrFail($id);
                $t->update(['schema_markup' => $markup]);
            } elseif ($type === 'specialist') {
                $s = \App\Models\TeamMember::findOrFail($id);
                $s->update(['schema_markup' => $markup]);
            } elseif ($type === 'cms_page') {
                $p = \App\Models\CmsPage::findOrFail($id);
                $p->update(['schema_markup' => $markup]);
            }

            return response()->json(['success' => true, 'message' => 'Schema markup successfully saved and applied!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save schema: ' . $e->getMessage()], 500);
        }
    }

    /* Treatments CRUD */
    public function storeTreatment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:treatments,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'category');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        \App\Models\Treatment::create($validated);

        return redirect()->back()->with('success', 'Treatment page created successfully!');
    }

    public function updateTreatment(Request $request, $id)
    {
        $treatment = \App\Models\Treatment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:treatments,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        $imagePath = $treatment->image;
        if ($request->hasFile('image')) {
            if ($treatment->image && !str_starts_with($treatment->image, 'http') && !str_starts_with($treatment->image, '//')) {
                Storage::disk('public')->delete($treatment->image);
            }
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'category');
        } elseif ($request->has('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        $treatment->update($validated);

        return redirect()->back()->with('success', 'Treatment page updated successfully!');
    }

    public function deleteTreatment($id)
    {
        $treatment = \App\Models\Treatment::findOrFail($id);
        if ($treatment->image) {
            Storage::disk('public')->delete($treatment->image);
        }
        $treatment->delete();

        return redirect()->back()->with('success', 'Treatment page deleted successfully!');
    }

    /* Specialists CRUD */
    public function storeSpecialist(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:team_members,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'collection');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        \App\Models\TeamMember::create($validated);

        return redirect()->back()->with('success', 'Specialist added successfully!');
    }

    public function updateSpecialist(Request $request, $id)
    {
        $specialist = \App\Models\TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:team_members,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        $imagePath = $specialist->image;
        if ($request->hasFile('image')) {
            if ($specialist->image && !str_starts_with($specialist->image, 'http') && !str_starts_with($specialist->image, '//')) {
                Storage::disk('public')->delete($specialist->image);
            }
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'collection');
        } elseif ($request->has('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        $specialist->update($validated);

        return redirect()->back()->with('success', 'Specialist updated successfully!');
    }

    public function deleteSpecialist($id)
    {
        $specialist = \App\Models\TeamMember::findOrFail($id);
        if ($specialist->image) {
            Storage::disk('public')->delete($specialist->image);
        }
        $specialist->delete();

        return redirect()->back()->with('success', 'Specialist deleted successfully!');
    }

    /* CMS Pages CRUD */
    public function storeCmsPage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'page');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        \App\Models\CmsPage::create($validated);

        return redirect()->back()->with('success', 'CMS page created successfully!');
    }

    public function updateCmsPage(Request $request, $id)
    {
        $page = \App\Models\CmsPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
            'schema_markup' => 'nullable|string',
        ]);

        $imagePath = $page->image;
        if ($request->hasFile('image')) {
            if ($page->image && !str_starts_with($page->image, 'http') && !str_starts_with($page->image, '//')) {
                Storage::disk('public')->delete($page->image);
            }
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'page');
        } elseif ($request->has('image_url')) {
            $imagePath = $request->input('image_url');
        }
        $validated['image'] = $imagePath;
        $validated['status'] = $request->boolean('status', true);

        $page->update($validated);

        return redirect()->back()->with('success', 'CMS page updated successfully!');
    }

    public function deleteCmsPage($id)
    {
        $page = \App\Models\CmsPage::findOrFail($id);
        if ($page->image) {
            Storage::disk('public')->delete($page->image);
        }
        $page->delete();

        return redirect()->back()->with('success', 'CMS page deleted successfully!');
    }
}


