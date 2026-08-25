<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\TeamMember;
use App\Models\Lead;
use App\Models\SocialLink;
use App\Models\SiteParameter;
use Illuminate\Support\Str;

class MobileApiController extends Controller
{
    /**
     * Mobile Dashboard Summary (Analytics, Leads & Activity)
     */
    public function dashboard(Request $request)
    {
        $adminController = new AdminController();
        $gaData = $adminController->getGoogleAnalyticsData($request)->getData(true);

        $totalBlogs = Blog::count();
        $totalServices = Service::count();
        $totalTreatments = Treatment::count();
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $totalSocialLinks = SocialLink::count();
        $totalClicks = SocialLink::sum('clicks');

        $recentLeads = Lead::orderBy('created_at', 'desc')->take(5)->get();
        $recentSocialLinks = SocialLink::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'success' => true,
            'analytics' => $gaData['data'] ?? [],
            'is_demo' => $gaData['is_demo'] ?? true,
            'counts' => [
                'blogs' => $totalBlogs,
                'services' => $totalServices,
                'treatments' => $totalTreatments,
                'leads' => $totalLeads,
                'new_leads' => $newLeads,
                'social_links' => $totalSocialLinks,
                'total_clicks' => $totalClicks
            ],
            'recent_leads' => $recentLeads,
            'recent_links' => $recentSocialLinks
        ]);
    }

    /**
     * Mobile Blogs Management
     */
    public function getBlogs(Request $request)
    {
        $query = Blog::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")->orWhere('category', 'like', "%{$search}%");
        }
        $blogs = $query->orderBy('published_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'blogs' => $blogs
        ]);
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $id = $request->input('id');
        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));
        
        $blogData = [
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'summary' => $request->input('summary', Str::limit(strip_tags($request->content), 150)),
            'category' => $request->input('category', 'General'),
            'image' => $request->input('image', 'assets/images/default-blog.jpg'),
            'author' => $request->input('author', 'Dr. Sumit Z'),
            'metatitle' => $request->input('metatitle', $request->title),
            'metadescription' => $request->input('metadescription', Str::limit(strip_tags($request->content), 160)),
            'metakeywords' => $request->input('metakeywords', ''),
            'published_at' => $request->input('published_at', now())
        ];

        if ($id) {
            $blog = Blog::findOrFail($id);
            $blog->update($blogData);
        } else {
            $blog = Blog::create($blogData);
        }

        return response()->json([
            'success' => true,
            'message' => $id ? 'Blog post updated successfully' : 'Blog post published successfully',
            'blog' => $blog
        ]);
    }

    /**
     * Unique Social Media Link Generator (UTM Tagging & Tracking)
     */
    public function generateSocialLink(Request $request)
    {
        $request->validate([
            'target_url' => 'required|url',
            'platform' => 'required|string'
        ]);

        $targetUrl = $request->input('target_url');
        $platform = strtolower($request->input('platform'));
        $medium = $request->input('utm_medium', 'social_post');
        $campaign = $request->input('utm_campaign', 'mobile_share_' . date('Ym'));
        $title = $request->input('title', 'Social Post Link');

        // Append UTM query params to URL
        $parsed = parse_url($targetUrl);
        $query = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }
        $query['utm_source'] = $platform;
        $query['utm_medium'] = $medium;
        $query['utm_campaign'] = $campaign;

        $queryString = http_build_query($query);
        $fullUrl = $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '') . ($parsed['path'] ?? '/') . '?' . $queryString;

        // Generate unique 6-char code
        $code = Str::lower(Str::random(6));
        while (SocialLink::where('code', $code)->exists()) {
            $code = Str::lower(Str::random(6));
        }

        $link = SocialLink::create([
            'code' => $code,
            'original_url' => $targetUrl,
            'utm_source' => $platform,
            'utm_medium' => $medium,
            'utm_campaign' => $campaign,
            'full_url' => $fullUrl,
            'title' => $title,
            'clicks' => 0
        ]);

        $baseUrl = config('app.url', 'https://www.drsumitz.com');
        $shortUrl = rtrim($baseUrl, '/') . '/s/' . $code;

        return response()->json([
            'success' => true,
            'short_url' => $shortUrl,
            'full_url' => $fullUrl,
            'code' => $code,
            'link' => $link
        ]);
    }

    public function getSocialLinks(Request $request)
    {
        $links = SocialLink::orderBy('created_at', 'desc')->paginate(20);
        return response()->json([
            'success' => true,
            'links' => $links
        ]);
    }

    /**
     * Shareable Entities List (Blogs, Services, Treatments for quick 1-tap link creation)
     */
    public function getShareableEntities(Request $request)
    {
        $baseUrl = config('app.url', 'https://www.drsumitz.com');

        $blogs = Blog::select('id', 'title', 'slug', 'category', 'image')->latest()->take(20)->get()->map(function($b) use ($baseUrl) {
            return [
                'type' => 'blog',
                'id' => $b->id,
                'title' => $b->title,
                'url' => rtrim($baseUrl, '/') . '/blog/' . $b->slug,
                'subtitle' => 'Blog • ' . $b->category
            ];
        });

        $services = Service::select('id', 'title', 'slug')->get()->map(function($s) use ($baseUrl) {
            return [
                'type' => 'service',
                'id' => $s->id,
                'title' => $s->title,
                'url' => rtrim($baseUrl, '/') . '/services/' . $s->slug,
                'subtitle' => 'Service Page'
            ];
        });

        $treatments = Treatment::select('id', 'title', 'slug')->get()->map(function($t) use ($baseUrl) {
            return [
                'type' => 'treatment',
                'id' => $t->id,
                'title' => $t->title,
                'url' => rtrim($baseUrl, '/') . '/treatments/' . $t->slug,
                'subtitle' => 'Treatment Page'
            ];
        });

        return response()->json([
            'success' => true,
            'entities' => array_merge($blogs->toArray(), $services->toArray(), $treatments->toArray())
        ]);
    }

    /**
     * Leads List
     */
    public function getLeads(Request $request)
    {
        $query = Lead::query();
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $leads = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'leads' => $leads
        ]);
    }

    public function updateLeadStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->status = $request->input('status', 'contacted');
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Public Short URL Redirector (/s/{code})
     */
    public function redirectShortLink($code)
    {
        $link = SocialLink::where('code', $code)->firstOrFail();
        $link->increment('clicks');

        return redirect()->away($link->full_url);
    }
}
