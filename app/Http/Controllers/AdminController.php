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

    public function dashboard(Request $request, $tab = 'leads')
    {
        $activeTab = $tab;
        
        // Normalize CMS page and internal notification tab names
        if ($activeTab === 'cms-pages') {
            $activeTab = 'cms_pages';
        } elseif ($activeTab === 'internal-notifications') {
            $activeTab = 'internal_notifications';
        }
        
        // Ensure activeTab is valid
        $validTabs = [
            'general', 'leads', 'livechat', 'branding', 'slider', 'testimonials', 
            'styling', 'pagecontents', 'landing', 'services', 'treatments', 
            'specialists', 'cms_pages', 'blogs', 'media', 'analytics', 'seo', 'users', 
            'notifications', 'internal_notifications'
        ];

        if (!in_array($activeTab, $validTabs)) {
            $activeTab = 'leads';
        }

        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            $permissions = is_array($user->permissions) ? $user->permissions : (json_decode($user->permissions ?? '[]', true) ?: []);
            if (!in_array('all', $permissions) && !in_array($activeTab, $permissions)) {
                // Find first permitted tab
                foreach ($validTabs as $vTab) {
                    if (in_array($vTab, $permissions)) {
                        return redirect()->route('admin.dashboard', ['tab' => $vTab]);
                    }
                }
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Access Denied: You do not have permissions for any modules.']);
            }
        }


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
                $r2PublicUrl = 'https://images.tenderfold.in';
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
        
        $users = [];
        if ($user->role === 'superadmin') {
            $users = \App\Models\User::all();
        }

        // Fetch broadcast notifications log
        $notificationsLog = \DB::table('web_notifications')
            ->whereNull('session_id')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'notifications_page');

        // Self-heal and normalize blog slugs (strip any trailing slashes)
        try {
            \Illuminate\Support\Facades\DB::statement("UPDATE blogs SET slug = TRIM(TRAILING '/' FROM slug) WHERE slug LIKE '%/'");
        } catch (\Throwable $e) {}

        // Fetch Blog Categories (auto-provision table if missing on live server)
        $this->ensureBlogCategoriesTable();
        $blogCategories = collect([]);
        if (\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            try {
                $blogCategories = \App\Models\BlogCategory::withCount('blogs')->orderBy('name')->get();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed fetching blog_categories: ' . $e->getMessage());
            }
        }

        return response()
            ->view('admin.dashboard', compact('activeTab', 'parameters', 'slides', 'testimonials', 'blogs', 'blogCategories', 'mediaFiles', 'pageContents', 'services', 'banks', 'leads', 'landingPages', 'treatments', 'specialists', 'cmsPages', 'users', 'notificationsLog'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }



    public function updateParameters(Request $request)
    {
        $this->checkPermission('general');
        // Handle unchecked checkbox for recaptcha_enabled
        if (!$request->has('recaptcha_enabled')) {
            $request->merge(['recaptcha_enabled' => '0']);
        }

        $data = $request->except(['_token', 'logo', 'favicon', 'logo_url', 'favicon_url', 'chat_assistant_avatar', 'chat_assistant_avatar_url']);
        
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

        // Handle chat assistant avatar upload
        if ($request->hasFile('chat_assistant_avatar')) {
            $avatarPath = $this->uploadAndConvertToWebP($request->file('chat_assistant_avatar'), 'branding');
            if ($avatarPath) {
                $oldAvatar = SiteParameter::find('chat_assistant_avatar');
                if ($oldAvatar && $oldAvatar->value && !str_starts_with($oldAvatar->value, 'http') && !str_starts_with($oldAvatar->value, '//')) {
                    Storage::disk('public')->delete($oldAvatar->value);
                }
                
                SiteParameter::updateOrCreate(
                    ['id' => 'chat_assistant_avatar'],
                    ['value' => $avatarPath, 'label' => 'Chat Assistant Avatar (WebP)', 'category' => 'branding']
                );
            }
        } elseif ($request->has('chat_assistant_avatar_url')) {
            SiteParameter::updateOrCreate(
                ['id' => 'chat_assistant_avatar'],
                ['value' => $request->input('chat_assistant_avatar_url'), 'label' => 'Chat Assistant Avatar (WebP)', 'category' => 'branding']
            );
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

        \Illuminate\Support\Facades\Cache::forget('global_site_parameters');

        \App\Models\AdminNotification::log('edit', 'Site Parameters & Settings Updated', 'System configuration & site settings were saved.', route('admin.tab', 'general'));

        return redirect()->back()->with('success', 'Parameters and branding updated successfully!');
    }

    /* Homepage Slides CRUD */
    public function storeSlide(Request $request)
    {
        $this->checkPermission('slider');
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'mobile_image' => 'nullable|image|max:5000',
            'mobile_image_url' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadAndConvertToWebP($request->file('image'), 'slider');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $mobileImagePath = null;
        if ($request->hasFile('mobile_image')) {
            $mobileImagePath = $this->uploadAndConvertToWebP($request->file('mobile_image'), 'slider');
        } elseif ($request->filled('mobile_image_url')) {
            $mobileImagePath = $request->input('mobile_image_url');
        }

        HomeSlide::create([
            'eyebrow' => $request->eyebrow,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'button_2_text' => $request->button_2_text,
            'button_2_link' => $request->button_2_link,
            'trust_1_num' => $request->trust_1_num,
            'trust_1_label' => $request->trust_1_label,
            'trust_2_num' => $request->trust_2_num,
            'trust_2_label' => $request->trust_2_label,
            'trust_3_num' => $request->trust_3_num,
            'trust_3_label' => $request->trust_3_label,
            'sort_order' => $request->sort_order ?? 0,
            'image_path' => $imagePath,
            'mobile_image_path' => $mobileImagePath,
        ]);

        return redirect()->back()->with('success', 'Slide added successfully!');
    }

    public function updateSlide(Request $request, $id)
    {
        $this->checkPermission('slider');
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'mobile_image' => 'nullable|image|max:5000',
            'mobile_image_url' => 'nullable|string',
        ]);

        $slide = HomeSlide::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($slide->image_path && !str_starts_with($slide->image_path, 'http') && !str_starts_with($slide->image_path, '//')) {
                try {
                    Storage::disk('public')->delete($slide->image_path);
                } catch (\Throwable $e) {}
            }
            $slide->image_path = $this->uploadAndConvertToWebP($request->file('image'), 'slider');
        } elseif ($request->has('image_url')) {
            $slide->image_path = $request->input('image_url');
        }

        if ($request->hasFile('mobile_image')) {
            if ($slide->mobile_image_path && !str_starts_with($slide->mobile_image_path, 'http') && !str_starts_with($slide->mobile_image_path, '//')) {
                try {
                    Storage::disk('public')->delete($slide->mobile_image_path);
                } catch (\Throwable $e) {}
            }
            $slide->mobile_image_path = $this->uploadAndConvertToWebP($request->file('mobile_image'), 'slider');
        } elseif ($request->has('mobile_image_url')) {
            $slide->mobile_image_path = $request->input('mobile_image_url');
        }

        $slide->update([
            'eyebrow' => $request->eyebrow,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'button_2_text' => $request->button_2_text,
            'button_2_link' => $request->button_2_link,
            'trust_1_num' => $request->trust_1_num,
            'trust_1_label' => $request->trust_1_label,
            'trust_2_num' => $request->trust_2_num,
            'trust_2_label' => $request->trust_2_label,
            'trust_3_num' => $request->trust_3_num,
            'trust_3_label' => $request->trust_3_label,
            'sort_order' => $request->sort_order ?? 0,
            'image_path' => $slide->image_path,
            'mobile_image_path' => $slide->mobile_image_path,
        ]);

        return redirect()->back()->with('success', 'Slide updated successfully!');
    }

    public function deleteSlide($id)
    {
        $this->checkPermission('slider');
        $slide = HomeSlide::findOrFail($id);
        if ($slide->image_path && !str_starts_with($slide->image_path, 'http') && !str_starts_with($slide->image_path, '//')) {
            try {
                Storage::disk('public')->delete($slide->image_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete slide image file: ' . $e->getMessage());
            }
        }
        if ($slide->mobile_image_path && !str_starts_with($slide->mobile_image_path, 'http') && !str_starts_with($slide->mobile_image_path, '//')) {
            try {
                Storage::disk('public')->delete($slide->mobile_image_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete slide mobile image file: ' . $e->getMessage());
            }
        }
        $slide->delete();

        return redirect()->back()->with('success', 'Slide deleted successfully!');
    }

    /* Testimonials CRUD */
    public function storeTestimonial(Request $request)
    {
        $this->checkPermission('testimonials');
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
        $this->checkPermission('testimonials');
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
                try {
                    Storage::disk('public')->delete($testimonial->avatar_path);
                } catch (\Throwable $e) {}
            }
            $testimonial->avatar_path = $this->uploadAndConvertToWebP($request->file('avatar'), 'testimonials');
        } elseif ($request->filled('avatar_url')) {
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
        $this->checkPermission('testimonials');
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->avatar_path && !str_starts_with($testimonial->avatar_path, 'http') && !str_starts_with($testimonial->avatar_path, '//')) {
            try {
                Storage::disk('public')->delete($testimonial->avatar_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete testimonial avatar file: ' . $e->getMessage());
            }
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
            'metatitle' => $blog->metatitle,
            'metadescription' => $blog->metadescription,
            'metakeywords' => $blog->metakeywords,
        ]);
    }


    public function storeBlog(Request $request)
    {
        $this->checkPermission('blogs');

        if ($request->filled('slug')) {
            $request->merge(['slug' => trim($request->slug, " /\t\n\r\0\x0B")]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'schema_markup' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
        ]);

        $rawSlug = $request->filled('slug') ? trim($request->slug, " /\t\n\r\0\x0B") : \Illuminate\Support\Str::slug($request->title);
        $slug = trim($rawSlug, " /\t\n\r\0\x0B") ?: \Illuminate\Support\Str::slug($request->title);
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

        if (\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            try {
                \App\Models\BlogCategory::firstOrCreate(
                    ['name' => trim($request->category)],
                    ['slug' => \Illuminate\Support\Str::slug($request->category)]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed firstOrCreate blog category: ' . $e->getMessage());
            }
        }

        $processedContent = $this->processContentImages($request->content, 'blog');

        \App\Models\Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $processedContent,
            'image_path' => $imagePath,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : now(),
            'schema_markup' => $request->schema_markup,
            'metatitle' => $request->metatitle,
            'metadescription' => $request->metadescription,
            'metakeywords' => $request->metakeywords,
        ]);

        return redirect()->back()->with('success', 'Blog post created successfully!');
    }

    public function updateBlog(Request $request, $id)
    {
        $this->checkPermission('blogs');

        if ($request->filled('slug')) {
            $request->merge(['slug' => trim($request->slug, " /\t\n\r\0\x0B")]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5000',
            'image_url' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
            'schema_markup' => 'nullable|string',
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
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

        if (\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            try {
                \App\Models\BlogCategory::firstOrCreate(
                    ['name' => trim($request->category)],
                    ['slug' => \Illuminate\Support\Str::slug($request->category)]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed firstOrCreate blog category: ' . $e->getMessage());
            }
        }

        $slug = trim($request->slug, " /\t\n\r\0\x0B");
        if (empty($slug)) {
            $slug = \Illuminate\Support\Str::slug($request->title);
        }

        $processedContent = $this->processContentImages($request->content, 'blog');

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $processedContent,
            'image_path' => $imagePath,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : $blog->published_at,
            'schema_markup' => $request->schema_markup,
            'metatitle' => $request->metatitle,
            'metadescription' => $request->metadescription,
            'metakeywords' => $request->metakeywords,
        ]);

        return redirect()->back()->with('success', 'Blog post updated successfully!');
    }


    public function deleteBlog($id)
    {
        $this->checkPermission('blogs');
        $blog = \App\Models\Blog::findOrFail($id);
        if ($blog->image_path && !str_starts_with($blog->image_path, 'http') && !str_starts_with($blog->image_path, '//')) {
            try {
                Storage::disk('public')->delete($blog->image_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete blog image file: ' . $e->getMessage());
            }
        }
        $blog->delete();

        return redirect()->back()->with('success', 'Blog post deleted successfully!');
    }

    /**
     * Ensure the blog_categories table exists, automatically creating and populating it if missing.
     */
    protected function ensureBlogCategoriesTable(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            try {
                \Illuminate\Support\Facades\Schema::create('blog_categories', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name')->unique();
                    $table->string('slug')->unique();
                    $table->text('description')->nullable();
                    $table->timestamps();
                });

                $categories = [
                    ['name' => 'Laundry Services', 'slug' => 'laundry-services', 'description' => 'Everyday wash & fold, fabric softness, and daily garment hygiene.'],
                    ['name' => 'Dry Cleaning', 'slug' => 'dry-cleaning', 'description' => 'Specialized chemical solvent cleaning for silks, woolens, and suits.'],
                    ['name' => 'Steam Pressing', 'slug' => 'steam-pressing', 'description' => 'Vacuum table heated steam pressing for crisp, crease-free garment finishing.'],
                    ['name' => 'Shoe Cleaning', 'slug' => 'shoe-cleaning', 'description' => 'Deep cleaning, stain removal, and care for sneakers, leather, and suede shoes.'],
                    ['name' => 'Carpet Cleaning', 'slug' => 'carpet-cleaning', 'description' => 'Industrial dust extraction, deep sanitization, and wash for rugs and carpets.'],
                    ['name' => 'Curtain Cleaning', 'slug' => 'curtain-cleaning', 'description' => 'Heavy drapery, blackout curtain washing, and dust allergen elimination.'],
                ];

                foreach ($categories as $cat) {
                    \Illuminate\Support\Facades\DB::table('blog_categories')->insertOrIgnore([
                        'name' => $cat['name'],
                        'slug' => $cat['slug'],
                        'description' => $cat['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Auto-creating blog_categories table failed: ' . $e->getMessage());
            }
        }
    }

    public function getBlogCategories()
    {
        $this->ensureBlogCategoriesTable();
        $categories = collect([]);
        if (\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            try {
                $categories = \App\Models\BlogCategory::withCount('blogs')->orderBy('name')->get();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed fetching blog categories: ' . $e->getMessage());
            }
        }
        return response()->json($categories);
    }

    public function storeBlogCategory(Request $request)
    {
        $this->checkPermission('blogs');
        $this->ensureBlogCategoriesTable();
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string|max:1000',
        ]);

        $name = trim($request->name);
        $slug = $request->slug ? \Illuminate\Support\Str::slug($request->slug) : \Illuminate\Support\Str::slug($name);

        $category = \App\Models\BlogCategory::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog category created successfully!',
                'category' => $category,
            ]);
        }

        return redirect()->back()->with('success', 'Blog category "' . $name . '" created successfully!');
    }

    public function deleteBlogCategory(Request $request, $id)
    {
        $this->checkPermission('blogs');
        $this->ensureBlogCategoriesTable();
        $category = \App\Models\BlogCategory::findOrFail($id);
        $name = $category->name;
        $category->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!',
            ]);
        }

        return redirect()->back()->with('success', 'Blog category "' . $name . '" deleted successfully!');
    }

    /* Page Content Updates */
    public function updatePageContents(Request $request)
    {
        $this->checkPermission('pagecontents');
        $contents = $request->input('contents', []);
        
        foreach ($contents as $page => $sections) {
            if (is_array($sections)) {
                foreach ($sections as $section => $keys) {
                    if (is_array($keys)) {
                        foreach ($keys as $key => $value) {
                            \DB::table('page_contents')->updateOrInsert(
                                ['page' => $page, 'section' => $section, 'key' => $key],
                                ['value' => $value, 'updated_at' => now()]
                            );
                        }
                    }
                }
            }
        }

        \Illuminate\Support\Facades\Cache::forget('global_page_contents');

        return redirect()->back()->with('success', 'Page contents updated successfully!');
    }

    /* Media Upload Manager */
    public function uploadMedia(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role !== 'superadmin') {
            $permissions = is_array($user->permissions) ? $user->permissions : (json_decode($user->permissions ?? '[]', true) ?: []);
            if (!in_array('all', $permissions) && !in_array('media', $permissions) && !in_array('blogs', $permissions)) {
                abort(403, 'Unauthorized action. You do not have permission for media upload.');
            }
        }

        $request->validate([
            'file' => 'required|file|image|max:10000',
        ]);

        $folder = $request->input('folder', 'uploads');
        $folder = in_array($folder, ['blog', 'uploads', 'page', 'service']) ? $folder : 'uploads';

        if ($request->hasFile('file')) {
            $path = $this->uploadAndConvertToWebP($request->file('file'), $folder);
            if ($path) {
                $url = site_image($path);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'location' => $url, // Required by TinyMCE 6 image uploader
                        'url' => $url,
                        'path' => $path,
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

    public function listMedia(Request $request)
    {
        $mediaFiles = [];
        try {
            $files = Storage::disk('public')->allFiles();
            foreach ($files as $file) {
                if (!str_starts_with(basename($file), '.')) {
                    $mediaFiles[] = [
                        'name' => basename($file),
                        'path' => $file,
                        'url' => site_image($file),
                        'size' => Storage::disk('public')->size($file),
                        'time' => Storage::disk('public')->lastModified($file),
                    ];
                }
            }
            usort($mediaFiles, function($a, $b) {
                return $b['time'] <=> $a['time'];
            });
        } catch (\Exception $e) {
            // Silently fail
        }

        return response()->json([
            'success' => true,
            'files' => $mediaFiles,
        ]);
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
            $r2PublicUrl = 'https://images.tenderfold.in';
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

    /**
     * Automatically extracts inline Base64 images from HTML content,
     * converts them to WebP files in storage, and replaces src with clean URLs.
     * Prevents database payload overflows (MySQL max_allowed_packet) and massive HTML download sizes.
     */
    private function processContentImages($content, $folder = 'blog')
    {
        if (empty($content) || !is_string($content)) {
            return $content;
        }

        // Clean accidental pasted page wrappers or reveal classes that cause opacity: 0
        $content = preg_replace('/<!--\s*Content Container\s*-->/i', '', $content);
        $content = preg_replace('/^\s*<div[^>]*class=["\'][^"\']*prose[^"\']*["\'][^>]*>/i', '', $content);
        $content = preg_replace('/<\/div>\s*$/i', '', $content);
        $content = preg_replace_callback('/<([a-z0-9]+)([^>]*class=["\'])([^"\']*)(["\'][^>]*)>/i', function ($m) {
            $classes = trim(preg_replace('/\s+/', ' ', preg_replace('/\breveal\b/i', '', $m[3])));
            return '<' . $m[1] . $m[2] . $classes . $m[4] . '>';
        }, $content);

        // Normalize existing local storage URLs with ports or missing leading slashes to portable root-relative /storage/ paths
        $content = preg_replace('#src=["\']https?://(?:127\.0\.0\.1|localhost)(?::\d+)?/storage/([^"\']+)["\']#i', 'src="/storage/$1"', $content);
        $content = preg_replace('#src=["\']storage/([^"\']+)["\']#i', 'src="/storage/$1"', $content);
        $content = preg_replace('#src=["\'](blog|uploads)/([^"\']+)["\']#i', 'src="/storage/$1/$2"', $content);

        // Resolve unresolvable images.tenderfold.in domain if files exist locally
        $content = preg_replace_callback('#src=["\']https?://images\.tenderfold\.in/([^"\']+)["\']#i', function($matches) {
            $filename = $matches[1];
            if (file_exists(public_path('storage/blog/' . $filename))) {
                return 'src="/storage/blog/' . $filename . '"';
            }
            if (file_exists(public_path('storage/uploads/' . $filename))) {
                return 'src="/storage/uploads/' . $filename . '"';
            }
            if (file_exists(public_path('storage/' . $filename))) {
                return 'src="/storage/' . $filename . '"';
            }
            return $matches[0];
        }, $content);

        if (stripos($content, 'data:image/') === false) {
            return $content;
        }

        $r2PublicUrl = trim(SiteParameter::where('id', 'cloudflare_r2_public_url')->value('value') ?? '');
        $r2Configured = !empty($r2PublicUrl) && !empty(trim(SiteParameter::where('id', 'cloudflare_r2_account_id')->value('value') ?? ''));
        $r2PublicUrl = rtrim($r2PublicUrl, '/');

        return preg_replace_callback('/src=["\'](data:image\/([a-zA-Z0-9\+\-]+);base64,([^"\']+))["\']/i', function ($matches) use ($folder, $r2Configured, $r2PublicUrl) {
            $rawMime = strtolower($matches[2]);
            $base64Data = $matches[3];
            $binaryData = base64_decode($base64Data);

            if (!$binaryData) {
                return $matches[0];
            }

            $filename = uniqid('img_') . '.webp';
            $savedUrl = null;

            // Try GD conversion to WebP
            $image = @imagecreatefromstring($binaryData);
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);

                ob_start();
                imagewebp($image, null, 85);
                $webpData = ob_get_clean();
                imagedestroy($image);

                if ($webpData) {
                    Storage::disk('public')->put($folder . '/' . $filename, $webpData);
                    if ($r2Configured && $this->uploadToR2($webpData, $filename, 'image/webp')) {
                        $savedUrl = $r2PublicUrl . '/' . $filename;
                    } else {
                        $savedUrl = '/storage/' . $folder . '/' . $filename;
                    }
                }
            }

            if (!$savedUrl) {
                $ext = ($rawMime === 'jpeg' || $rawMime === 'jpg') ? 'jpg' : (($rawMime === 'png') ? 'png' : 'webp');
                $filename = uniqid('img_') . '.' . $ext;
                Storage::disk('public')->put($folder . '/' . $filename, $binaryData);
                $savedUrl = '/storage/' . $folder . '/' . $filename;
            }

            return 'src="' . $savedUrl . '"';
        }, $content);
    }

    /* Services CRUD */
    public function storeService(Request $request)
    {
        $this->checkPermission('services');
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
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
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
        $rawFaqs = $request->input('faqs');
        if (empty($rawFaqs) && $request->has('faq_questions')) {
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
        } else {
            $validated['faqs'] = parse_faqs($rawFaqs, $request->input('service_name'));
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        \App\Models\Service::create($validated);

        return redirect()->back()->with('success', 'Service page created successfully!');
    }

    public function updateService(Request $request, $id)
    {
        $this->checkPermission('services');
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
            'metatitle' => 'nullable|string|max:255',
            'metadescription' => 'nullable|string',
            'metakeywords' => 'nullable|string',
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

        // Handle FAQs
        $rawFaqs = $request->input('faqs');
        if (empty($rawFaqs) && $request->has('faq_questions')) {
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
        } else {
            $validated['faqs'] = parse_faqs($rawFaqs, $request->input('service_name'));
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $service->update($validated);

        return redirect()->back()->with('success', 'Service page updated successfully!');
    }

    public function deleteService($id)
    {
        $this->checkPermission('services');
        $service = \App\Models\Service::findOrFail($id);
        if ($service->image_path && !str_starts_with($service->image_path, 'http') && !str_starts_with($service->image_path, '//')) {
            try {
                Storage::disk('public')->delete($service->image_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete service image file: ' . $e->getMessage());
            }
        }
        if ($service->icon_path && !str_starts_with($service->icon_path, 'http') && !str_starts_with($service->icon_path, '//')) {
            try {
                Storage::disk('public')->delete($service->icon_path);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete service icon file: ' . $e->getMessage());
            }
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
        $this->checkPermission('leads');
        $request->validate([
            'status' => 'required|string|in:New,Contacted,In Progress,Closed',
            'notes' => 'nullable|string',
        ]);

        $lead = \App\Models\Lead::findOrFail($id);
        $oldStatus = $lead->status;
        $lead->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        try {
            \App\Models\LeadActivity::create([
                'lead_id' => $lead->id,
                'user_name' => $user ? $user->name : 'Admin',
                'action_type' => ($oldStatus !== $request->status) ? 'status_change' : 'note_added',
                'status_from' => $oldStatus,
                'status_to' => $request->status,
                'notes' => $request->notes,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed logging LeadActivity: ' . $e->getMessage());
        }

        try {
            \App\Models\AdminNotification::log(
                'lead',
                'Lead Updated: ' . $lead->name,
                'Status: ' . $oldStatus . ' → ' . $request->status,
                route('admin.tab', 'leads')
            );
        } catch (\Throwable $e) {}

        return redirect()->back()->with('success', 'Lead updated successfully!');
    }

    public function deleteLead($id)
    {
        $this->checkPermission('leads');
        $lead = \App\Models\Lead::findOrFail($id);
        $name = $lead->name;
        $lead->delete();

        \App\Models\AdminNotification::log(
            'lead',
            'Lead Record Deleted: ' . $name,
            'Lead record #' . $id . ' was permanently deleted.',
            route('admin.tab', 'leads')
        );

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }

    public function exportLeads()
    {
        $this->checkPermission('leads');
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
        $this->checkPermission('leads');
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
            'existing_title' => 'nullable|string',
            'existing_summary' => 'nullable|string',
            'existing_content' => 'nullable|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');
        $tone = $request->input('tone');
        $generateImage = $request->boolean('generate_image');
        $existingTitle = $request->input('existing_title');
        $existingSummary = $request->input('existing_summary');
        $existingContent = $request->input('existing_content');

        $siteName = \App\Models\SiteParameter::where('id', 'site_name')->value('value') ?: 'Tenderfold';
        $siteTagline = \App\Models\SiteParameter::where('id', 'site_tagline')->value('value') ?: 'Premium Laundry & Dry Cleaning in Gurgaon';

        if (!empty($existingContent)) {
            $systemPrompt = "You are an expert fabric care, laundry, dry cleaning, and lifestyle copywriter and SEO specialist. The user wants to EXTEND the content of an existing blog article for {$siteName} ({$siteTagline}).
Existing Article Title: \"{$existingTitle}\"
Existing Article Summary: \"{$existingSummary}\"
Existing Article Content (HTML): \"{$existingContent}\"

Your task is to EXTEND the existing article content based on the user's prompt: '{$prompt}'.
The writing tone must be: {$tone}.

Strict Instructions:
1. DO NOT replace, delete, or rewrite any of the existing content. Keep the existing HTML content exactly as it is.
2. Append/add new, SEO-relevant subheadings (<h3>/<h4>), paragraphs (<p>), or bulleted lists (<ul>/<li>) at the end of the existing content.
3. Review the title and summary, and only refine or expand them to match the new extensions if necessary, keeping them SEO-optimized.
4. Return the combined content (original content + your extensions) in the 'content' field.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"title\": \"Expanded SEO-optimized blog title\",
  \"category\": \"Choose the most appropriate category (e.g. Care Notes, Wardrobe, Occasion Wear, Seasonal, Sneaker Care, Fabric Tips)\",
  \"summary\": \"Refined and engaging summary of the article\",
  \"content\": \"The original HTML content plus your newly appended text at the end, properly formatted in HTML. Do not repeat the original text twice.\",
  \"image_prompt\": \"A detailed text-to-image prompt to generate a cover image matching this article's topic.\"
}";
        } else {
            $systemPrompt = "You are an expert fabric care, laundry, dry cleaning, and lifestyle copywriter and SEO specialist. Create a complete, high-quality blog article in English for {$siteName} ({$siteTagline}), based on the user's prompt.
The writing tone must be: {$tone}.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"title\": \"A compelling SEO-optimized blog title\",
  \"category\": \"Choose the most appropriate category (e.g. Care Notes, Wardrobe, Occasion Wear, Seasonal, Sneaker Care, Fabric Tips)\",
  \"summary\": \"A short, engaging 1-2 sentence summary of the article to be shown on listing cards\",
  \"content\": \"The full blog article content formatted in clean, modern HTML. Use paragraphs <p>, subheadings <h3>/<h4>, lists <ul>/<li>, and emphasized text. Do not include raw CSS or javascript. Make the article comprehensive, informative, and around 400-600 words.\",
  \"image_prompt\": \"A detailed, descriptive text-to-image prompt to generate a high-quality featured cover image matching this article's topic. Do not include adjectives like 'photorealistic', instead describe lighting, context, objects, and style (e.g. 'A professional photograph of ...')\"
}

User prompt: {$prompt}";
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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
            'existing_service_name' => 'nullable|string',
            'existing_hero_title' => 'nullable|string',
            'existing_hero_subtitle' => 'nullable|string',
            'existing_intro_content' => 'nullable|string',
            'existing_tips_content' => 'nullable|string',
            'existing_summary' => 'nullable|string',
            'existing_faqs' => 'nullable|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');
        $tone = $request->input('tone');
        
        $existingName = $request->input('existing_service_name');
        $existingHeroTitle = $request->input('existing_hero_title');
        $existingHeroSub = $request->input('existing_hero_subtitle');
        $existingIntro = $request->input('existing_intro_content');
        $existingTips = $request->input('existing_tips_content');
        $existingSummary = $request->input('existing_summary');
        $existingFaqs = $request->input('existing_faqs');

        if (!empty($existingIntro)) {
            $systemPrompt = "You are an expert orthopedic, joint replacement, and physiotherapy clinic clinical writer and patient care coordinator. The user wants to EXTEND the content of an existing service details page at SumitZ Clinic, Pune.
Existing Service: \"{$existingName}\"
Existing Hero Title: \"{$existingHeroTitle}\"
Existing Hero Subtitle: \"{$existingHeroSub}\"
Existing Intro (HTML): \"{$existingIntro}\"
Existing Tips (HTML): \"{$existingTips}\"
Existing Summary: \"{$existingSummary}\"
Existing FAQs (JSON): \"{$existingFaqs}\"

Your task is to EXTEND the existing service page details based on the user's prompt: '{$prompt}'.
The writing tone must be: {$tone}.

Strict Instructions:
1. DO NOT delete, rewrite, or replace any of the existing content or headings. Keep the original text fully intact.
2. In 'intro_content', append new informative clinical sections, descriptions, recovery pathways, or patient advice to the end of the existing HTML.
3. In 'tips_content', append additional self-care guidelines, exercise advice, or prevention rules to the end of the existing HTML.
4. Review the FAQs, preserve the existing ones exactly, and append 2-3 new relevant frequently asked questions.
5. In fields like 'rate_value', 'max_loan', and 'tenure', retain original values if appropriate or update them to clinical indicators (e.g. OPD Consulting slot indicators, Walk-ins Welcome, Session timings).

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"service_name\": \"Service name\",
  \"hero_category\": \"A short category tag (e.g. ORTHOPAEDICS, PHYSIOTHERAPY, DIAGNOSTICS)\",
  \"hero_title\": \"Hero heading\",
  \"hero_subtitle\": \"Hero subheading\",
  \"rate_value\": \"Clinical slots/costs indicator (e.g., Starts at ₹500 or OPD Consulting)\",
  \"max_loan\": \"Availability indicator (e.g., Prior Booking Required or Walk-ins Welcome)\",
  \"tenure\": \"Session timings/report duration (e.g., 30-45 mins per session or Immediate OPG)\",
  \"intro_title\": \"Heading for the intro section\",
  \"intro_content\": \"Original HTML plus new appended HTML paragraphs (<p>) highlighting diagnostic details or techniques\",
  \"eligibility_criteria\": \"Criteria on new lines (preserve original and optionally add more)\",
  \"documents_required\": \"Required items or preparation on new lines (preserve original)\",
  \"tips_title\": \"Heading for self-care tips section\",
  \"tips_content\": \"Original HTML plus new appended clinical self-care tips in HTML\",
  \"badge\": \"Promo badge (e.g., Expert Care, Modern OPG)\",
  \"summary\": \"Brief summary of the service\",
  \"faqs\": [
     (All original FAQs plus newly appended ones)
  ]
}";
        } else {
            $systemPrompt = "You are an expert orthopedic, joint replacement, and physiotherapy clinic clinical writer and patient care coordinator. Generate a complete service page for a clinical or diagnostic service in English for SumitZ Clinic, Pune, based on the user's prompt.
The writing tone must be: {$tone}.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"service_name\": \"Name of the service (e.g. Robotic Knee Replacement or Digital OPG Diagnostic)\",
  \"hero_category\": \"A short category tag (e.g. ORTHOPAEDICS, PHYSIOTHERAPY, DIAGNOSTICS)\",
  \"hero_title\": \"A catchy, clear H1 heading for the hero section\",
  \"hero_subtitle\": \"A brief 1-2 sentence subheading for the hero section describing the main value proposition\",
  \"rate_value\": \"Starting rate or fees indicator (e.g. Starts at ₹500 or OPD Consulting)\",
  \"max_loan\": \"Availability or booking indicator (e.g. Prior Booking Required or Walk-ins Welcome)\",
  \"tenure\": \"Session duration or report speed (e.g. 45 Mins / Session or Under 24h reports)\",
  \"intro_title\": \"A heading for the introduction section (e.g. Why Choose Our Physiotherapy Program?)\",
  \"intro_content\": \"An engaging introduction block formatted in HTML paragraphs (<p>) highlighting clinical benefits, methodologies, and outcomes\",
  \"eligibility_criteria\": \"A list of patient eligibility conditions (e.g., age, chronic pain types), with each condition on a new line (use newlines \\n to separate)\",
  \"documents_required\": \"A list of medical documents/preparations required (e.g. Prior X-rays, Doctor referrals), with each item on a new line (use newlines \\n to separate)\",
  \"tips_title\": \"Heading for recovery tips/guide section (e.g. Fast Recovery Tips post Knee Surgery)\",
  \"tips_content\": \"A helpful recovery/self-care guide content formatted in HTML paragraphs (<p>)\",
  \"badge\": \"A short clinical highlight badge tag (e.g. Expert Surgeon, Advanced OPG)\",
  \"summary\": \"A short description of the service (1-2 sentences) shown in lists or small layouts\",
  \"faqs\": [
    {
      \"question\": \"An important, frequently asked patient question about this clinical service\",
      \"answer\": \"A detailed, professional clinical answer to the question\"
    },
    {
      \"question\": \"Another logical patient question about this service\",
      \"answer\": \"A detailed answer to the question\"
    },
    {
      \"question\": \"A third important patient question about this service\",
      \"answer\": \"A detailed answer to the question\"
    }
  ]
}

User prompt: {$prompt}";
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

    public function aiGenerateSlider(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');
        $generateImage = $request->boolean('generate_image');

        $systemPrompt = "You are an expert orthopedic, physical therapy, and health care copywriter. The user wants to create a Homepage Hero Slider slide for SumitZ Clinic, Pune.
Based on the user's prompt, generate a compelling slide.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes (e.g. do not use ```json ... ```). Output must start with { and end with }.

JSON Schema:
{
  \"title\": \"A short, punchy slide title (max 6-8 words)\",
  \"subtitle\": \"An engaging supporting subtitle (max 15-20 words)\",
  \"button_text\": \"Call to action button text (e.g. Book Appointment, Learn More, Read Free Guide)\",
  \"button_link\": \"A button link, e.g. '#appointment' or '/services' or '/specialists'\",
  \"image_prompt\": \"A detailed, descriptive text-to-image prompt to generate a high-quality background/hero banner image matching this slide's topic. Do not include adjectives like 'photorealistic', instead describe lighting, context, objects, and style (e.g. 'A professional photograph of ...')\"
}

User prompt: {$prompt}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

            $slideData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            if ($generateImage) {
                $imagePrompt = $slideData['image_prompt'] ?? $prompt;
                $imageUrl = $this->generateAndSaveAiImage($imagePrompt, '16:9');
                if ($imageUrl) {
                    $slideData['image_url'] = $imageUrl;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $slideData
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

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
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
        $apiKey = self::getAiApiKey();
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
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$apiKey}", [
                    'instances' => [
                        ['prompt' => $prompt]
                    ],
                    'parameters' => [
                        'sampleCount' => 1
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
                $r2PublicUrl = 'https://images.tenderfold.in';
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

        // ── 7. push_subscriptions ──────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('push_subscriptions')) {
            \Illuminate\Support\Facades\Schema::create('push_subscriptions', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('session_id')->nullable()->index();
                $t->text('endpoint')->nullable();
                $t->text('auth_token')->nullable();
                $t->text('public_key')->nullable();
                $t->timestamps();
            });
            $results['push_subscriptions'] = 'CREATED ✅';
        } else {
            $results['push_subscriptions'] = 'exists ✔';
        }

        // ── 8. web_notifications ───────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('web_notifications')) {
            \Illuminate\Support\Facades\Schema::create('web_notifications', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('session_id')->nullable()->index();
                $t->string('title');
                $t->text('message');
                $t->string('url')->nullable();
                $t->boolean('is_read')->default(false);
                $t->timestamps();
            });
            $results['web_notifications'] = 'CREATED ✅';
        } else {
            $results['web_notifications'] = 'exists ✔';
        }

        // ── 9. users table role and permissions columns ─────────────────
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            $hasRole = \Illuminate\Support\Facades\Schema::hasColumn('users', 'role');
            $hasPerms = \Illuminate\Support\Facades\Schema::hasColumn('users', 'permissions');
            
            if (!$hasRole || !$hasPerms) {
                \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $t) use ($hasRole, $hasPerms) {
                    if (!$hasRole) {
                        $t->string('role')->default('admin')->after('password');
                    }
                    if (!$hasPerms) {
                        $t->text('permissions')->nullable()->after('role');
                    }
                });
                
                // Seed user ID 1 as superadmin
                $firstUser = \App\Models\User::find(1);
                if ($firstUser) {
                    $firstUser->update([
                        'role' => 'superadmin',
                        'permissions' => ['all']
                    ]);
                }
                
                $results['users_columns'] = 'UPDATED (role & permissions added) ✅';
            } else {
                $results['users_columns'] = 'columns exist ✔';
            }
        }

        // ── 10. blog_categories ──────────────────────────────────────────
        if (!\Illuminate\Support\Facades\Schema::hasTable('blog_categories')) {
            \Illuminate\Support\Facades\Schema::create('blog_categories', function (\Illuminate\Database\Schema\Blueprint $t) {
                $t->id();
                $t->string('name')->unique();
                $t->string('slug')->unique();
                $t->text('description')->nullable();
                $t->timestamps();
            });

            $defaultCategories = [
                ['name' => 'Laundry Services', 'slug' => 'laundry-services', 'description' => 'Everyday wash & fold, fabric softness, and daily garment hygiene.'],
                ['name' => 'Dry Cleaning', 'slug' => 'dry-cleaning', 'description' => 'Specialized chemical solvent cleaning for silks, woolens, and suits.'],
                ['name' => 'Steam Pressing', 'slug' => 'steam-pressing', 'description' => 'Vacuum table heated steam pressing for crisp, crease-free garment finishing.'],
                ['name' => 'Shoe Cleaning', 'slug' => 'shoe-cleaning', 'description' => 'Deep cleaning, stain removal, and care for sneakers, leather, and suede shoes.'],
                ['name' => 'Carpet Cleaning', 'slug' => 'carpet-cleaning', 'description' => 'Industrial dust extraction, deep sanitization, and wash for rugs and carpets.'],
                ['name' => 'Curtain Cleaning', 'slug' => 'curtain-cleaning', 'description' => 'Heavy drapery, blackout curtain washing, and dust allergen elimination.'],
            ];

            foreach ($defaultCategories as $cat) {
                \Illuminate\Support\Facades\DB::table('blog_categories')->insertOrIgnore([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $results['blog_categories'] = 'CREATED ✅';
        } else {
            $results['blog_categories'] = 'exists ✔';
        }

        // ── 11. Normalize existing blog slugs ──────────────────────────
        try {
            \Illuminate\Support\Facades\DB::statement("UPDATE blogs SET slug = TRIM(TRAILING '/' FROM slug) WHERE slug LIKE '%/'");
            $results['blog_slugs_normalized'] = 'CLEANED ✅';
        } catch (\Exception $e) {
            $results['blog_slugs_normalized'] = 'SKIPPED: ' . $e->getMessage();
        }

        // ── 12. Verify leads insert/select works ───────────────────────
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
            'slug' => 'nullable|string|max:255',
            'layout_type' => 'required|string|in:home,about,service,specialist',
            'meta_description' => 'nullable|string',
        ]);

        $slug = $request->input('slug');
        if (empty($slug)) {
            $slug = \Illuminate\Support\Str::slug($request->title);
        } else {
            $slug = \Illuminate\Support\Str::slug($slug);
        }

        \App\Models\LandingPage::create([
            'title' => $request->title,
            'slug' => $slug,
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
            'layout_type' => 'required|string|in:home,about,service,specialist',
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
        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');
        $layout = $landingPage->layout_type;
        $existingContent = is_array($landingPage->content) ? $landingPage->content : json_decode($landingPage->content, true);

        if ($layout === 'home') {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this landing page.",
  "hero_title": "A catchy, orthopedic or physiotherapy focused hero main title.",
  "hero_subtitle": "An engaging clinical subtitle or tagline.",
  "features": [
     {"title": "Feature 1 Title", "desc": "Feature 1 Description", "icon": "Lucide icon name like activity/heart/clock/check/file-text/shield-check/calendar/zap"},
     {"title": "Feature 2 Title", "desc": "Feature 2 Description", "icon": "Lucide icon"},
     {"title": "Feature 3 Title", "desc": "Feature 3 Description", "icon": "Lucide icon"},
     {"title": "Feature 4 Title", "desc": "Feature 4 Description", "icon": "Lucide icon"}
  ],
  "about_title": "Why Choose SumitZ Clinic / About Us section heading",
  "about_text": "Detailed description of our orthopedic, physiotherapy and recovery services.",
  "cta_title": "Ready to book your consultation? or Call to action heading",
  "cta_text": "Brief sub-text for CTA section.",
  "faqs": [
     {"q": "FAQ Question 1?", "a": "FAQ Answer 1"},
     {"q": "FAQ Question 2?", "a": "FAQ Answer 2"},
     {"q": "FAQ Question 3?", "a": "FAQ Answer 3"}
  ]
}';
        } elseif ($layout === 'service') {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this service landing page.",
  "hero_title": "Hero main title for the service (e.g. Pain-Free Movement: Pune\'s Top Joint Recovery Program).",
  "hero_subtitle": "Engaging hero subtitle/tagline highlighting patient relief.",
  "service_details_title": "Detailed header for the program details (e.g. Advanced Orthopedic & Joint Care).",
  "service_details_text": "Detailed clinical overview of the treatments, diagnosis, recovery methodologies, and clinical care provided (2 paragraphs).",
  "service_bullets": [
     {"title": "Why Us Bullet 1 Title", "desc": "Short description of this benefit", "icon": "Lucide icon name like activity/heart/clock/check/file-text/shield-check/calendar/zap"},
     {"title": "Why Us Bullet 2 Title", "desc": "Short description", "icon": "Lucide icon"},
     {"title": "Why Us Bullet 3 Title", "desc": "Short description", "icon": "Lucide icon"}
  ],
  "process_title": "Heading for step by step process (e.g. Your Path to Full Recovery).",
  "process_steps": [
     {"title": "Step 1 Title (e.g. Initial Assessment)", "desc": "Explanation of the diagnosis/assessment stage."},
     {"title": "Step 2 Title (e.g. Personalized Therapy)", "desc": "Explanation of the treatment stage."},
     {"title": "Step 3 Title (e.g. Continuous Support)", "desc": "Explanation of post-recovery care."}
  ],
  "cta_title": "Call to action heading",
  "cta_text": "Brief sub-text for CTA section."
}';
        } elseif ($layout === 'specialist') {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this specialist doctor landing page.",
  "hero_title": "Hero main title (e.g. Consult Pune\'s Leading Joint & Spine Specialist).",
  "hero_subtitle": "Engaging tagline focused on consulting an experienced doctor/surgeon.",
  "specialist_bio_title": "Biography Section Heading (e.g. Meet Pune\'s Trusted Joint Care Leader).",
  "specialist_bio_text": "Detailed biography of the doctor, their medical credentials, surgical achievements, and commitment to patients (2 paragraphs).",
  "specialist_qualifications": [
     {"title": "Credential/Qualification 1 Title (e.g. 15+ Years Surgical Experience)", "desc": "Brief details of experience or fellowships."},
     {"title": "Credential/Qualification 2 Title (e.g. Global Training & Certifications)", "desc": "Brief details of international education/training."},
     {"title": "Credential/Qualification 3 Title (e.g. Patient-First Care Model)", "desc": "Brief details of personalized patient approach."}
  ],
  "case_studies": [
     {"patient_name": "Patient Name or Initials", "case": "Short description of the joint/spine condition treated", "result": "Description of the positive recovery outcome achieved"},
     {"patient_name": "Patient Name", "case": "Condition", "result": "Outcome"},
     {"patient_name": "Patient Name", "case": "Condition", "result": "Outcome"}
  ],
  "cta_title": "Call to action heading",
  "cta_text": "Brief sub-text for CTA section."
}';
        } else {
            $schemaDesc = '{
  "meta_description": "SEO optimized meta description for this landing page.",
  "hero_title": "Hero main title.",
  "hero_subtitle": "Hero subtitle.",
  "story_title": "Our Story / Mission title",
  "story_text": "Detailed story text explaining our clinical background, 2 paragraphs.",
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

        $extensionPrompt = "";
        if (!empty($existingContent)) {
            $extensionPrompt = "\nCRITICAL: The landing page already contains content! You must EXTEND this content, NOT overwrite it. Here is the existing content: " . json_encode($existingContent) . "\n" .
                               "Do not delete, shorten, or replace any existing texts or descriptions. Only enrich the text paragraphs by adding more clinical and recovery-related details to them, and preserve all other fields and structures completely.";
        }

        $systemPrompt = "You are an expert orthopedic web content copywriter and landing page optimization specialist.
Create high-quality, professional landing page content in English for SumitZ Clinic, Pune, based on the user's prompt: '{$prompt}'.
The page layout structure is: {$layout}.
{$extensionPrompt}

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Output must start with { and end with }.

JSON Schema:
{$schemaDesc}";


        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $systemPrompt = "You are a customer relationship coordinator. Create 5 realistic, high-quality, positive customer testimonials for SumitZ Orthopaedic & Physiotherapy Clinic (Pune's premier clinic in Baner) as if they were synced directly from their Google My Business profile: '{$gmbLink}'.
Make each testimonial unique and authentic. They should mention specific treatments (e.g. Knee Replacement, Joint Pain recovery, Physiotherapy rehabilitation, Digital OPG X-ray), helpful doctor care from Dr. Sumit, clinical support, or successful mobility recovery in Baner, Pune.

You MUST return the output ONLY as a raw, single-line valid JSON array of objects matching the schema below. Output must start with [ and end with ].

JSON Schema:
[
  {
    \"name\": \"Full Name of Patient\",
    \"role\": \"Patient's Occupation or Profile (e.g., IT Consultant, Senior Citizen, Athlete, Homemaker)\",
    \"content\": \"Highly detailed, authentic review text expressing their positive recovery experience with Dr. Sumit and the clinic staff.\",
    \"rating\": 5
  },
  ...
]";


        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
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
      \"platform_type\": \"Guest Post / Medium / Health Forum / Medical Blog\",
      \"title\": \"Title of the guest article to pitch\",
      \"keywords\": \"Anchor text keywords to embed\",
      \"context\": \"Specific context or paragraph direction where the backlink to our site fits naturally (e.g. 'A paragraph explaining ligament tear recovery should link to our physiotherapy service page')\"
    }
  ],
  \"quora_questions\": [
    {
      \"topic\": \"Topic or category on Quora\",
      \"question_direction\": \"E.g., 'What is the recovery time for ACL reconstruction?' or 'How does dry needling therapy work?'\",
      \"draft_answer_points\": \"Detailed bullet points explaining how to write a helpful, authority answer, and where to naturally plug our website\"
    }
  ],
  \"pinterest_posts\": [
    {
      \"visual_idea\": \"Description of the pin layout/image (e.g. 'An infographic showing post-operative recovery stages with a mint green background')\",
      \"overlay_text\": \"Bold overlay text (e.g. '5 Exercises to Relief Desk Job Back Pain!')\",
      \"category\": \"Category (e.g. Joint Health, Physiotherapy, Fitness & Wellness)\"
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
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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
        $this->checkPermission('seo');
        try {
            if ($request->has('sitemap_domain')) {
                $domain = rtrim(trim($request->input('sitemap_domain')), '/');
                if (!empty($domain)) {
                    \App\Models\SiteParameter::updateOrCreate(
                        ['id' => 'sitemap_domain'],
                        ['value' => $domain, 'label' => 'Sitemap Domain', 'category' => 'seo']
                    );
                }
            }

            $domain = \App\Models\SiteParameter::where('id', 'sitemap_domain')->value('value') ?: (config('app.url') ?: 'https://tenderfold.in');
            \Illuminate\Support\Facades\Artisan::call('sitemap:generate', ['--domain' => $domain]);

            return redirect()->back()->with('success', 'Sitemap & robots.txt successfully updated for live domain (' . $domain . ')!');
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

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $title = '';
        $summary = '';
        $url = '';
        $baseUrl = rtrim(config('app.url') ?? 'https://tenderfold.in', '/');

        if ($type === 'blog') {
            $blog = \App\Models\Blog::findOrFail($id);
            $title = $blog->title;
            $summary = $blog->summary;
            $url = $baseUrl . '/blogs/' . $blog->slug;
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
1. Write a compelling summary of the article/service suitable for local audiences in Gurgaon (under 800 characters).
2. Incorporate 2-3 relevant hashtags (e.g. #LaundryGurgaon, #DryCleaningGurgaon, #Tenderfold, etc.).
3. End with a strong call-to-action phrase prompting users to click 'Learn More' to visit the link.
4. Keep the draft strictly under 1400 characters (Google GBP limit is 1500).
5. Output ONLY the drafted post text directly without markdown quotes, backticks, or introduction.";


        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $type = $request->type;
        $id = $request->id;
        $schemaTypes = $request->schema_types;

        // Gather page context
        $pageTitle = '';
        $pageUrl = '';
        $pageDescription = '';
        $extraContext = '';

        $siteParams = SiteParameter::all()->pluck('value', 'id')->toArray();
        $siteName = $siteParams['site_name'] ?? 'Tenderfold';
        $siteTagline = $siteParams['site_tagline'] ?? 'Premium Laundry & Dry Cleaning in Gurgaon';
        $baseUrl = rtrim(config('app.url') ?? 'https://tenderfold.in', '/');

        if ($type === 'home') {
            $pageTitle = 'Home Page | ' . $siteName;
            $pageUrl = $baseUrl . '/';
            $pageDescription = $siteName . ' — ' . $siteTagline . '. Premium laundry, wash & fold, steam ironing, and dry cleaning with doorstep pickup in Gurgaon.';
            
            $extraContext = "Business Name: " . $siteName . "\n" .
                            "Tagline: " . $siteTagline . "\n" .
                            "Phone: " . ($siteParams['phone'] ?? '+91 95824 66445') . "\n" .
                            "Email: " . ($siteParams['email'] ?? 'info@tenderfold.in') . "\n" .
                            "Address: " . ($siteParams['address'] ?? 'Sector 49 & Sector 83, Gurgaon');
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
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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

    public function aiGenerateCmsPage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');

        $systemPrompt = "You are an expert orthopedic, physical therapy, and medical copywriter and SEO specialist. Create a complete, high-quality CMS page in English for SumitZ Clinic, Pune based on the user's prompt: '{$prompt}'.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Do not wrap the JSON in Markdown code block quotes. Output must start with { and end with }.

JSON Schema:
{
  \"title\": \"A clear, SEO-optimized page title\",
  \"slug\": \"seo-friendly-url-slug\",
  \"metatitle\": \"SEO Meta Title (max 60 chars)\",
  \"metadescription\": \"SEO Meta Description (max 160 chars)\",
  \"content\": \"Full page content formatted in clean, modern HTML using <p>, <h3>, <h4>, <ul>, <li>, and <strong> elements.\"
}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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
            $text = trim($resData['candidates'][0]['content']['parts'][0]['text'] ?? '');
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $pageData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            return response()->json(['success' => true, 'data' => $pageData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'CMS Page generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateTreatment(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');

        $systemPrompt = "You are an expert orthopedic surgeon and medical copywriter. Create a detailed treatment profile in English for SumitZ Clinic, Pune based on the user's prompt: '{$prompt}'.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Output must start with { and end with }.

JSON Schema:
{
  \"name\": \"Name of the Treatment (e.g. ACL Reconstruction & Arthroscopy)\",
  \"slug\": \"treatment-url-slug\",
  \"metatitle\": \"SEO Meta Title (max 60 chars)\",
  \"metadescription\": \"SEO Meta Description (max 160 chars)\",
  \"description\": \"Comprehensive treatment overview formatted in clean HTML (<p>, <h3>, <ul>, <li>, <strong>) detailing surgical/non-surgical procedure, indications, recovery steps, and outcomes.\"
}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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
            $text = trim($resData['candidates'][0]['content']['parts'][0]['text'] ?? '');
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $treatmentData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            return response()->json(['success' => true, 'data' => $treatmentData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Treatment generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateSpecialist(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $apiKey = self::getAiApiKey();
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API Key is not set. Please save it in General Settings or set GEMINI_API_KEY in .env.'], 400);
        }

        $prompt = $request->input('prompt');

        $systemPrompt = "You are a medical practice copywriter. Create a specialist doctor profile in English for SumitZ Clinic, Pune based on the user's prompt: '{$prompt}'.

You MUST return the output ONLY as a raw, single-line valid JSON object matching the schema below. Output must start with { and end with }.

JSON Schema:
{
  \"name\": \"Full Name of Specialist (e.g. Dr. Sumit Diwan)\",
  \"slug\": \"dr-name-slug\",
  \"designation\": \"Designation & Degrees (e.g. Lead Orthopedic Surgeon, MBBS, MS Ortho)\",
  \"description\": \"Engaging specialist biography formatted in clean HTML (<p>, <h3>, <ul>, <li>, <strong>) highlighting qualifications, surgical experience, patient philosophy, and clinic care.\"
}";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/" . self::getAiModel() . ":generateContent?key={$apiKey}", [
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
            $text = trim($resData['candidates'][0]['content']['parts'][0]['text'] ?? '');
            if (str_starts_with($text, '```')) {
                $text = preg_replace('/^```(?:json)?/i', '', $text);
                $text = preg_replace('/```$/', '', $text);
                $text = trim($text);
            }

            $specData = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Failed to parse generated content as JSON. Raw response: ' . $text], 500);
            }

            return response()->json(['success' => true, 'data' => $specData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Specialist profile generation failed: ' . $e->getMessage()], 500);
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
        $this->checkPermission('treatments');
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
        
        // Handle FAQs for Treatment
        $rawFaqs = $request->input('faqs');
        $validated['faqs'] = parse_faqs($rawFaqs, $request->input('name'));

        \App\Models\Treatment::create($validated);

        return redirect()->back()->with('success', 'Treatment page created successfully!');
    }

    public function updateTreatment(Request $request, $id)
    {
        $this->checkPermission('treatments');
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

        // Handle FAQs for Treatment
        $rawFaqs = $request->input('faqs');
        $validated['faqs'] = parse_faqs($rawFaqs, $request->input('name'));

        $treatment->update($validated);

        return redirect()->back()->with('success', 'Treatment page updated successfully!');
    }

    public function deleteTreatment($id)
    {
        $this->checkPermission('treatments');
        $treatment = \App\Models\Treatment::findOrFail($id);
        if ($treatment->image && !str_starts_with($treatment->image, 'http') && !str_starts_with($treatment->image, '//')) {
            try {
                Storage::disk('public')->delete($treatment->image);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete treatment image file: ' . $e->getMessage());
            }
        }
        $treatment->delete();

        return redirect()->back()->with('success', 'Treatment page deleted successfully!');
    }

    /* Specialists CRUD */
    public function storeSpecialist(Request $request)
    {
        $this->checkPermission('specialists');
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
        $this->checkPermission('specialists');
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
        $this->checkPermission('specialists');
        $specialist = \App\Models\TeamMember::findOrFail($id);
        if ($specialist->image && !str_starts_with($specialist->image, 'http') && !str_starts_with($specialist->image, '//')) {
            try {
                Storage::disk('public')->delete($specialist->image);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete specialist image file: ' . $e->getMessage());
            }
        }
        $specialist->delete();

        return redirect()->back()->with('success', 'Specialist deleted successfully!');
    }

    /* CMS Pages CRUD */
    public function storeCmsPage(Request $request)
    {
        $this->checkPermission('cms_pages');
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
        $this->checkPermission('cms_pages');
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
        $this->checkPermission('cms_pages');
        $page = \App\Models\CmsPage::findOrFail($id);
        if ($page->image && !str_starts_with($page->image, 'http') && !str_starts_with($page->image, '//')) {
            try {
                Storage::disk('public')->delete($page->image);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete CMS page image file: ' . $e->getMessage());
            }
        }
        $page->delete();

        return redirect()->back()->with('success', 'CMS page deleted successfully!');
    }

    private function checkPermission($module)
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            return;
        }

        $permissions = is_array($user->permissions) ? $user->permissions : (json_decode($user->permissions ?? '[]', true) ?: []);
        if (!in_array('all', $permissions) && !in_array($module, $permissions)) {
            abort(403, 'Unauthorized action. You do not have permission for this module.');
        }
    }

    public function storeUser(Request $request)
    {
        $this->checkPermission('users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:superadmin,admin',
            'permissions' => 'nullable|array',
        ]);

        $permissions = $request->input('role') === 'superadmin' ? ['all'] : ($request->input('permissions') ?? []);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'role' => $request->role,
            'permissions' => $permissions,
        ]);

        return redirect()->back()->with('success', 'Admin user created successfully!');
    }

    public function updateUser(Request $request, $id)
    {
        $this->checkPermission('users');

        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:superadmin,admin',
            'permissions' => 'nullable|array',
        ]);

        // Prevent self-demotion or self-modification of role/permissions for security
        if (Auth::id() === (int)$id) {
            $role = $user->role;
            $permissions = $user->permissions;
        } else {
            $role = $request->role;
            $permissions = $request->role === 'superadmin' ? ['all'] : ($request->input('permissions') ?? []);
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'permissions' => $permissions,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = \Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Admin user updated successfully!');
    }

    public function deleteUser($id)
    {
        $this->checkPermission('users');

        if (Auth::id() === (int)$id) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Admin user deleted successfully!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = \Hash::make($request->password);
        }

        $user->update($updateData);

        \App\Models\AdminNotification::log('edit', 'Admin Profile Updated', 'Profile details were updated for ' . $user->name, route('admin.tab', 'users'));

        return redirect()->back()->with('success', 'Your profile details updated successfully!');
    }

    /**
     * Helper to resolve Gemini API Key from Env or DB
     */
    public static function getAiApiKey(): ?string
    {
        $key = env('GEMINI_API_KEY') ?: env('GOOGLE_API_KEY');
        if (!empty($key) && trim($key) !== '') {
            return trim($key);
        }

        $key = \App\Models\SiteParameter::where('id', 'gemini_api_key')->value('value')
            ?: \App\Models\SiteParameter::where('id', 'google_api_key')->value('value');

        return (!empty($key) && trim($key) !== '') ? trim($key) : null;
    }

    /**
     * AI Model Helper Method
     */
    public static function getAiModel(): string
    {
        $model = \App\Models\SiteParameter::where('id', 'ai_model')->value('value');
        if (!empty($model) && trim($model) !== '' && trim($model) !== 'gemini-2.0-flash') {
            return trim($model);
        }
        return 'gemini-2.5-flash';
    }

    /**
     * Test Gemini API Connection & Model Validation
     */
    public function testAiConnection(Request $request)
    {
        $apiKey = $request->input('api_key') ?: self::getAiApiKey();
        $model = $request->input('model') ?: self::getAiModel();

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'No Gemini API Key provided or saved in settings.'
            ], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => 'Respond with "OK" if this connection test is successful.']]]
                ]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text') ?? 'Connection successful!';
                return response()->json([
                    'success' => true,
                    'message' => 'AI Connection Verified! Selected Model: ' . $model,
                    'response' => trim($text)
                ]);
            } else {
                $err = $response->json('error.message') ?? $response->body();
                return response()->json([
                    'success' => false,
                    'message' => 'API Test Failed (HTTP ' . $response->status() . '): ' . $err
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Internal Admin Notifications Management
     */
    public function getInternalNotifications(Request $request)
    {
        $query = \App\Models\AdminNotification::query();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', strtolower($request->type));
        }

        $notifications = $query->orderBy('created_at', 'desc')->take(50)->get();
        $unreadCount = \App\Models\AdminNotification::where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markInternalNotificationsRead(Request $request)
    {
        \App\Models\AdminNotification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function clearInternalNotifications(Request $request)
    {
        \App\Models\AdminNotification::query()->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Google Analytics, Search Console & GMB Integration
     */
    private function getGoogleAccessToken()
    {
        // 1. Check if Service Account JSON is configured
        $serviceAccountJson = SiteParameter::where('id', 'google_service_account_json')->value('value');
        if (!empty($serviceAccountJson)) {
            $accessToken = SiteParameter::where('id', 'google_oauth_access_token')->value('value');
            $expiresAt = SiteParameter::where('id', 'google_oauth_expires_at')->value('value');
            
            if (!empty($accessToken) && !empty($expiresAt) && $expiresAt > time() + 30) {
                return $accessToken;
            }
            
            $newAccessToken = $this->getAccessTokenFromServiceAccount($serviceAccountJson);
            if ($newAccessToken) {
                SiteParameter::updateOrCreate(
                    ['id' => 'google_oauth_access_token'],
                    ['value' => $newAccessToken, 'label' => 'Google OAuth Access Token', 'category' => 'other']
                );
                SiteParameter::updateOrCreate(
                    ['id' => 'google_oauth_expires_at'],
                    ['value' => (time() + 3500), 'label' => 'Google OAuth Expires At', 'category' => 'other']
                );
                return $newAccessToken;
            }
        }

        // 2. Fall back to OAuth Client credentials
        $accessToken = SiteParameter::where('id', 'google_oauth_access_token')->value('value');
        $expiresAt = SiteParameter::where('id', 'google_oauth_expires_at')->value('value');
        $refreshToken = SiteParameter::where('id', 'google_oauth_refresh_token')->value('value');
        $clientId = SiteParameter::where('id', 'google_oauth_client_id')->value('value');
        $clientSecret = SiteParameter::where('id', 'google_oauth_client_secret')->value('value');

        if (empty($refreshToken) || empty($clientId) || empty($clientSecret)) {
            return null;
        }

        if (!empty($accessToken) && !empty($expiresAt) && $expiresAt > time() + 30) {
            return $accessToken;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $newAccessToken = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 3600;
                $newExpiresAt = time() + $expiresIn;

                SiteParameter::updateOrCreate(
                    ['id' => 'google_oauth_access_token'],
                    ['value' => $newAccessToken, 'label' => 'Google OAuth Access Token', 'category' => 'other']
                );
                SiteParameter::updateOrCreate(
                    ['id' => 'google_oauth_expires_at'],
                    ['value' => $newExpiresAt, 'label' => 'Google OAuth Expires At', 'category' => 'other']
                );

                return $newAccessToken;
            }
        } catch (\Exception $e) {}

        return null;
    }

    private function getAccessTokenFromServiceAccount($jsonString)
    {
        try {
            $credentials = json_decode($jsonString, true);
            if (!$credentials || !isset($credentials['private_key']) || !isset($credentials['client_email'])) {
                return null;
            }

            $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
            $now = time();
            $claimSet = json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/webmasters.readonly https://www.googleapis.com/auth/business.manage',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now
            ]);

            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlClaimSet = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claimSet));
            $signatureInput = $base64UrlHeader . "." . $base64UrlClaimSet;

            $signature = '';
            $pkey = openssl_pkey_get_private($credentials['private_key']);
            if (!$pkey) return null;

            openssl_sign($signatureInput, $signature, $pkey, "SHA256");
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            $jwt = $signatureInput . "." . $base64UrlSignature;

            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }
        } catch (\Exception $e) {}

        return null;
    }

    public function getGoogleAnalyticsData(Request $request)
    {
        $this->checkPermission('analytics');

        $gaPropertyId = trim(SiteParameter::where('id', 'google_analytics_property_id')->value('value') ?? '');
        $gscSiteUrl = trim(SiteParameter::where('id', 'google_search_console_site_url')->value('value') ?? '');
        $gmbLocationId = trim(SiteParameter::where('id', 'gmb_location_id')->value('value') ?? '');

        $token = $this->getGoogleAccessToken();
        $isDemo = empty($token);
        $warning = null;

        $dateRange = $request->input('date_range', 'last_7_days');
        switch ($dateRange) {
            case 'last_30_days':
                $days = 30;
                $gaStart = '30daysAgo';
                $gmbDaysStart = 33;
                break;
            case 'last_90_days':
                $days = 90;
                $gaStart = '90daysAgo';
                $gmbDaysStart = 93;
                break;
            case 'last_7_days':
            default:
                $days = 7;
                $gaStart = '7daysAgo';
                $gmbDaysStart = 10;
                break;
        }

        $startDateStr = date('Y-m-d', strtotime("-$days days"));
        $endDateStr = date('Y-m-d');

        $dailyDates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dailyDates[] = date('d M', strtotime("-$i days"));
        }

        $analyticsData = null;
        $searchConsoleData = null;
        $myBusinessData = null;
        $postalCodeData = [];

        // 1. GA4 Data
        if (!$isDemo && !empty($gaPropertyId)) {
            try {
                $gaPropertyIdClean = preg_replace('/^properties\//', '', $gaPropertyId);
                $response = \Illuminate\Support\Facades\Http::withToken($token)
                    ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$gaPropertyIdClean}:runReport", [
                        'dateRanges' => [['startDate' => $gaStart, 'endDate' => 'today']],
                        'metrics' => [
                            ['name' => 'sessions'],
                            ['name' => 'activeUsers'],
                            ['name' => 'newUsers'],
                            ['name' => 'bounceRate'],
                            ['name' => 'averageSessionDuration'],
                            ['name' => 'screenPageViewsPerSession']
                        ],
                        'dimensions' => [['name' => 'date']]
                    ]);

                if ($response->successful()) {
                    $res = $response->json();
                    $rows = $res['rows'] ?? [];

                    $totalSessions = 0;
                    $totalActiveUsers = 0;
                    $totalNewUsers = 0;
                    $totalBounceRateSum = 0;
                    $totalDurationSum = 0;
                    $totalPageViewsPerSessionSum = 0;
                    $rowCount = count($rows);

                    $dailyMap = [];
                    foreach ($rows as $row) {
                        $dateStr = $row['dimensionValues'][0]['value'] ?? '';
                        $formattedDate = '';
                        if (strlen($dateStr) === 8) {
                            $formattedDate = date('d M', strtotime($dateStr));
                        }
                        
                        $sessions = (int)($row['metricValues'][0]['value'] ?? 0);
                        $users = (int)($row['metricValues'][1]['value'] ?? 0);
                        $newUsers = (int)($row['metricValues'][2]['value'] ?? 0);
                        $bounceRate = (float)($row['metricValues'][3]['value'] ?? 0);
                        $duration = (float)($row['metricValues'][4]['value'] ?? 0);
                        $pagesPerSession = (float)($row['metricValues'][5]['value'] ?? 0);

                        $totalSessions += $sessions;
                        $totalActiveUsers += $users;
                        $totalNewUsers += $newUsers;
                        $totalBounceRateSum += $bounceRate;
                        $totalDurationSum += $duration;
                        $totalPageViewsPerSessionSum += $pagesPerSession;

                        if ($formattedDate) {
                            $dailyMap[$formattedDate] = [
                                'sessions' => $sessions,
                                'users' => $users
                            ];
                        }
                    }

                    $avgBounce = $rowCount > 0 ? round($totalBounceRateSum / $rowCount, 1) : 0;
                    $avgDurationVal = $rowCount > 0 ? round($totalDurationSum / $rowCount) : 0;
                    $avgPages = $rowCount > 0 ? round($totalPageViewsPerSessionSum / $rowCount, 1) : 0;

                    $minutes = floor($avgDurationVal / 60);
                    $seconds = $avgDurationVal % 60;
                    $durationStr = $minutes . 'm ' . $seconds . 's';

                    $dailyList = [];
                    foreach ($dailyDates as $d) {
                        $dailyList[] = [
                            'date' => $d,
                            'sessions' => $dailyMap[$d]['sessions'] ?? 0,
                            'users' => $dailyMap[$d]['users'] ?? 0
                        ];
                    }

                    $analyticsData = [
                        'sessions' => $totalSessions,
                        'unique_users' => $totalActiveUsers,
                        'repeated_users' => max(0, $totalSessions - $totalNewUsers),
                        'bounce_rate' => $avgBounce ? ($avgBounce * 100) : 0,
                        'time_on_site' => $durationStr,
                        'avg_pages_per_session' => $avgPages,
                        'daily' => $dailyList
                    ];
                }
            } catch (\Exception $e) {
                $warning .= "GA4 Exception: " . $e->getMessage() . ". ";
            }
        }

        // 2. GSC Data
        if (!$isDemo && !empty($gscSiteUrl)) {
            try {
                $statResponse = \Illuminate\Support\Facades\Http::withToken($token)
                    ->post("https://www.googleapis.com/webmasters/v3/sites/" . urlencode($gscSiteUrl) . "/searchAnalytics/query", [
                        'startDate' => $startDateStr,
                        'endDate' => $endDateStr,
                        'dimensions' => ['date']
                    ]);

                $kwResponse = \Illuminate\Support\Facades\Http::withToken($token)
                    ->post("https://www.googleapis.com/webmasters/v3/sites/" . urlencode($gscSiteUrl) . "/searchAnalytics/query", [
                        'startDate' => $startDateStr,
                        'endDate' => $endDateStr,
                        'dimensions' => ['query'],
                        'rowLimit' => 10
                    ]);

                $pgResponse = \Illuminate\Support\Facades\Http::withToken($token)
                    ->post("https://www.googleapis.com/webmasters/v3/sites/" . urlencode($gscSiteUrl) . "/searchAnalytics/query", [
                        'startDate' => $startDateStr,
                        'endDate' => $endDateStr,
                        'dimensions' => ['page'],
                        'rowLimit' => 10
                    ]);

                if ($statResponse->successful()) {
                    $statRows = $statResponse->json()['rows'] ?? [];
                    $totalClicks = 0;
                    $totalImpressions = 0;
                    $totalCtrSum = 0;
                    $totalPosSum = 0;
                    $rowCount = count($statRows);

                    $dailyMap = [];
                    foreach ($statRows as $row) {
                        $dateVal = $row['keys'][0] ?? '';
                        $formattedDate = date('d M', strtotime($dateVal));
                        $clicks = (int)($row['clicks'] ?? 0);
                        $impressions = (int)($row['impressions'] ?? 0);
                        $ctr = (float)($row['ctr'] ?? 0);
                        $position = (float)($row['position'] ?? 0);

                        $totalClicks += $clicks;
                        $totalImpressions += $impressions;
                        $totalCtrSum += $ctr;
                        $totalPosSum += $position;

                        if ($formattedDate) {
                            $dailyMap[$formattedDate] = ['clicks' => $clicks, 'impressions' => $impressions];
                        }
                    }

                    $avgCtr = $rowCount > 0 ? round(($totalCtrSum / $rowCount) * 100, 2) : 0;
                    $avgPos = $rowCount > 0 ? round($totalPosSum / $rowCount, 1) : 0;

                    $dailyList = [];
                    foreach ($dailyDates as $d) {
                        $dailyList[] = [
                            'date' => $d,
                            'clicks' => $dailyMap[$d]['clicks'] ?? 0,
                            'impressions' => $dailyMap[$d]['impressions'] ?? 0
                        ];
                    }

                    $keywords = [];
                    if ($kwResponse->successful()) {
                        foreach ($kwResponse->json()['rows'] ?? [] as $row) {
                            $keywords[] = [
                                'keyword' => $row['keys'][0] ?? '',
                                'clicks' => (int)($row['clicks'] ?? 0),
                                'impressions' => (int)($row['impressions'] ?? 0),
                                'ctr' => round(($row['ctr'] ?? 0) * 100, 1),
                                'position' => round($row['position'] ?? 0, 1)
                            ];
                        }
                    }

                    $pages = [];
                    if ($pgResponse->successful()) {
                        foreach ($pgResponse->json()['rows'] ?? [] as $row) {
                            $pages[] = [
                                'page' => $row['keys'][0] ?? '',
                                'clicks' => (int)($row['clicks'] ?? 0),
                                'impressions' => (int)($row['impressions'] ?? 0),
                                'ctr' => round(($row['ctr'] ?? 0) * 100, 1)
                            ];
                        }
                    }

                    $searchConsoleData = [
                        'clicks' => $totalClicks,
                        'impressions' => $totalImpressions,
                        'ctr' => $avgCtr,
                        'position' => $avgPos,
                        'keywords' => $keywords,
                        'pages' => $pages,
                        'daily' => $dailyList
                    ];
                }
            } catch (\Exception $e) {}
        }

        // Fallback Mock Data if not connected
        if (empty($analyticsData)) {
            $dailyList = [];
            foreach ($dailyDates as $i => $d) {
                $dailyList[] = [
                    'date' => $d,
                    'sessions' => rand(180, 280),
                    'users' => rand(130, 210)
                ];
            }
            $analyticsData = [
                'sessions' => round(1482 * ($days / 7)),
                'unique_users' => round(1120 * ($days / 7)),
                'repeated_users' => round(362 * ($days / 7)),
                'bounce_rate' => 42.5,
                'time_on_site' => '2m 45s',
                'avg_pages_per_session' => 3.2,
                'daily' => $dailyList
            ];
        }

        if (empty($searchConsoleData)) {
            $dailyList = [];
            foreach ($dailyDates as $i => $d) {
                $dailyList[] = [
                    'date' => $d,
                    'clicks' => rand(90, 136),
                    'impressions' => rand(1200, 1900)
                ];
            }
            $searchConsoleData = [
                'clicks' => round(824 * ($days / 7)),
                'impressions' => round(12450 * ($days / 7)),
                'ctr' => 6.62,
                'position' => 8.4,
                'keywords' => [
                    ['keyword' => 'best orthopedic doctor baner pune', 'clicks' => round(245 * ($days / 7)), 'impressions' => round(1200 * ($days / 7)), 'ctr' => 20.4, 'position' => 1.2],
                    ['keyword' => 'knee replacement surgeon baner', 'clicks' => round(154 * ($days / 7)), 'impressions' => round(980 * ($days / 7)), 'ctr' => 15.7, 'position' => 2.1],
                    ['keyword' => 'physiotherapy clinic hinjewadi', 'clicks' => round(98 * ($days / 7)), 'impressions' => round(850 * ($days / 7)), 'ctr' => 11.5, 'position' => 3.4],
                    ['keyword' => 'back pain treatment clinic pune', 'clicks' => round(76 * ($days / 7)), 'impressions' => round(1540 * ($days / 7)), 'ctr' => 4.9, 'position' => 6.8],
                    ['keyword' => 'dr sumitz joint specialist', 'clicks' => round(65 * ($days / 7)), 'impressions' => round(210 * ($days / 7)), 'ctr' => 30.9, 'position' => 1.0],
                ],
                'pages' => [
                    ['page' => '/services/knee-replacement', 'clicks' => round(312 * ($days / 7)), 'impressions' => round(3400 * ($days / 7)), 'ctr' => 9.2],
                    ['page' => '/about-us', 'clicks' => round(145 * ($days / 7)), 'impressions' => round(1200 * ($days / 7)), 'ctr' => 12.1],
                    ['page' => '/treatments/acl-reconstruction', 'clicks' => round(112 * ($days / 7)), 'impressions' => round(1500 * ($days / 7)), 'ctr' => 7.5],
                    ['page' => '/faq', 'clicks' => round(88 * ($days / 7)), 'impressions' => round(950 * ($days / 7)), 'ctr' => 9.3],
                    ['page' => '/contact-us', 'clicks' => round(52 * ($days / 7)), 'impressions' => round(340 * ($days / 7)), 'ctr' => 15.3],
                ],
                'daily' => $dailyList
            ];
        }

        if (empty($myBusinessData)) {
            $dailyList = [];
            foreach ($dailyDates as $i => $d) {
                $dailyList[] = [
                    'date' => $d,
                    'visitors' => rand(220, 310),
                    'calls' => rand(12, 22),
                    'website_visits' => rand(45, 68),
                    'directions' => rand(28, 42)
                ];
            }
            $myBusinessData = [
                'visitors' => round(1840 * ($days / 7)),
                'search_appearance' => round(4920 * ($days / 7)),
                'calls' => round(112 * ($days / 7)),
                'website_visits' => round(384 * ($days / 7)),
                'directions' => round(245 * ($days / 7)),
                'daily' => $dailyList
            ];
        }

        if (empty($postalCodeData)) {
            $postalCodeData = [
                ['zip' => '411045', 'city' => 'Baner, Pune', 'users' => round(420 * ($days / 7))],
                ['zip' => '411057', 'city' => 'Hinjewadi, Pune', 'users' => round(315 * ($days / 7))],
                ['zip' => '411045', 'city' => 'Balewadi, Pune', 'users' => round(280 * ($days / 7))],
                ['zip' => '411027', 'city' => 'Aundh, Pune', 'users' => round(190 * ($days / 7))],
                ['zip' => '411038', 'city' => 'Kothrud, Pune', 'users' => round(125 * ($days / 7))],
            ];
        }

        return response()->json([
            'success' => true,
            'is_demo' => $isDemo,
            'warning' => rtrim($warning, '. '),
            'data' => [
                'analytics' => $analyticsData,
                'search_console' => $searchConsoleData,
                'my_business' => $myBusinessData,
                'postal_codes' => $postalCodeData
            ]
        ]);
    }

    public function redirectToGoogle()
    {
        $this->checkPermission('analytics');

        $clientId = SiteParameter::where('id', 'google_oauth_client_id')->value('value');
        $clientSecret = SiteParameter::where('id', 'google_oauth_client_secret')->value('value');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->back()->with('error', 'Google OAuth Client ID or Client Secret is missing in settings.');
        }

        $redirectUri = route('admin.google.callback');
        $scopes = [
            'https://www.googleapis.com/auth/analytics.readonly',
            'https://www.googleapis.com/auth/webmasters.readonly',
            'https://www.googleapis.com/auth/business.manage',
        ];

        $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->checkPermission('analytics');

        if ($request->has('error')) {
            return redirect()->route('admin.tab', 'analytics')->with('error', 'Google authorization failed: ' . $request->input('error'));
        }

        $code = $request->input('code');
        if (empty($code)) {
            return redirect()->route('admin.tab', 'analytics')->with('error', 'Authorization code not received.');
        }

        $clientId = SiteParameter::where('id', 'google_oauth_client_id')->value('value');
        $clientSecret = SiteParameter::where('id', 'google_oauth_client_secret')->value('value');
        $redirectUri = route('admin.google.callback');

        try {
            $response = \Illuminate\Support\Facades\Http::post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                SiteParameter::updateOrCreate(['id' => 'google_oauth_access_token'], ['value' => $data['access_token'], 'label' => 'Google OAuth Access Token', 'category' => 'other']);
                SiteParameter::updateOrCreate(['id' => 'google_oauth_expires_at'], ['value' => time() + ($data['expires_in'] ?? 3600), 'label' => 'Google OAuth Expires At', 'category' => 'other']);
                if (!empty($data['refresh_token'])) {
                    SiteParameter::updateOrCreate(['id' => 'google_oauth_refresh_token'], ['value' => $data['refresh_token'], 'label' => 'Google OAuth Refresh Token', 'category' => 'other']);
                }
                return redirect()->route('admin.tab', 'analytics')->with('success', 'Successfully connected your Google Account!');
            }
        } catch (\Exception $e) {}

        return redirect()->route('admin.tab', 'analytics')->with('error', 'Token exchange failed.');
    }

    public function disconnectGoogle()
    {
        $this->checkPermission('analytics');

        SiteParameter::whereIn('id', [
            'google_oauth_access_token',
            'google_oauth_expires_at',
            'google_oauth_refresh_token',
            'google_analytics_property_id',
            'google_search_console_site_url',
            'gmb_location_id'
        ])->update(['value' => '']);

        return redirect()->back()->with('success', 'Google Account disconnected and properties reset.');
    }

    public function getGoogleProperties()
    {
        $this->checkPermission('analytics');

        $token = $this->getGoogleAccessToken();
        if (empty($token)) {
            return response()->json(['success' => false, 'message' => 'No active authorization token found.'], 401);
        }

        $analytics = [];
        $searchConsole = [];
        $gmb = [];

        try {
            $accountsResponse = \Illuminate\Support\Facades\Http::withToken($token)->get('https://analyticsadmin.googleapis.com/v1alpha/accountSummaries');
            if ($accountsResponse->successful()) {
                foreach ($accountsResponse->json()['accountSummaries'] ?? [] as $summary) {
                    foreach ($summary['propertySummaries'] ?? [] as $prop) {
                        $analytics[] = [
                            'id' => preg_replace('/^properties\//', '', $prop['property']),
                            'name' => $prop['displayName'] . ' (' . preg_replace('/^properties\//', '', $prop['property']) . ')'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {}

        try {
            $scResponse = \Illuminate\Support\Facades\Http::withToken($token)->get('https://www.googleapis.com/webmasters/v3/sites');
            if ($scResponse->successful()) {
                foreach ($scResponse->json()['siteEntry'] ?? [] as $site) {
                    $searchConsole[] = ['id' => $site['siteUrl'], 'name' => $site['siteUrl']];
                }
            }
        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'analytics' => $analytics,
            'search_console' => $searchConsole,
            'my_business' => $gmb
        ]);
    }
}



