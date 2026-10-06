<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Models\Blog;
use App\Models\SiteParameter;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--domain= : Override base domain for sitemap generation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap and LLMs.txt for Tenderfold';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainOption = $this->option('domain');
        $savedDomain = SiteParameter::where('id', 'sitemap_domain')->value('value') ?: SiteParameter::where('id', 'site_url')->value('value');
        
        $baseUrl = $domainOption ?: ($savedDomain ?: config('app.url'));
        if (empty($baseUrl) || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $baseUrl = 'https://tenderfold.in';
        }
        $baseUrl = rtrim($baseUrl, '/');

        $siteParams = SiteParameter::all()->pluck('value', 'id')->toArray();
        $siteName = $siteParams['site_name'] ?? 'Tenderfold';
        $siteTagline = $siteParams['site_tagline'] ?? 'Premium Laundry & Dry Cleaning in Gurgaon';
        $phone = $siteParams['phone'] ?? '+91 95824 66445';

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static routes
        $staticRoutes = [
            '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/about-us' => ['priority' => '0.9', 'changefreq' => 'monthly'],
            '/services' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/pricing' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/testimonials' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            '/faq' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/blogs' => ['priority' => '0.9', 'changefreq' => 'daily'],
            '/contact-us' => ['priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $route => $config) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . $baseUrl . $route . '</loc>';
            $xml[] = '    <lastmod>' . Carbon::now()->startOfDay()->toW3cString() . '</lastmod>';
            $xml[] = '    <changefreq>' . $config['changefreq'] . '</changefreq>';
            $xml[] = '    <priority>' . $config['priority'] . '</priority>';
            $xml[] = '  </url>';
        }

        // Dynamic Services
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        foreach ($services as $service) {
            $lastMod = $service->updated_at ? $service->updated_at->toW3cString() : Carbon::now()->startOfDay()->toW3cString();
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . $baseUrl . '/services/' . $service->slug . '</loc>';
            $xml[] = '    <lastmod>' . $lastMod . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }

        // Dynamic Blogs
        $blogs = Blog::all();
        foreach ($blogs as $blog) {
            $lastMod = $blog->updated_at ? $blog->updated_at->toW3cString() : Carbon::now()->startOfDay()->toW3cString();
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . $baseUrl . '/blogs/' . $blog->slug . '</loc>';
            $xml[] = '    <lastmod>' . $lastMod . '</lastmod>';
            $xml[] = '    <changefreq>monthly</changefreq>';
            $xml[] = '    <priority>0.7</priority>';
            $xml[] = '  </url>';
        }

        // Dynamic Landing Pages
        if (\Illuminate\Support\Facades\Schema::hasTable('landing_pages')) {
            $landingPages = \App\Models\LandingPage::all();
            foreach ($landingPages as $lp) {
                $lastMod = $lp->updated_at ? $lp->updated_at->toW3cString() : Carbon::now()->startOfDay()->toW3cString();
                $xml[] = '  <url>';
                $xml[] = '    <loc>' . $baseUrl . '/l/' . $lp->slug . '</loc>';
                $xml[] = '    <lastmod>' . $lastMod . '</lastmod>';
                $xml[] = '    <changefreq>weekly</changefreq>';
                $xml[] = '    <priority>0.8</priority>';
                $xml[] = '  </url>';
            }
        }

        $xml[] = '</urlset>';

        $sitemapContent = implode("\n", $xml);
        file_put_contents(public_path('sitemap.xml'), $sitemapContent);
        $this->info('Sitemap successfully generated for domain: ' . $baseUrl . ' at ' . public_path('sitemap.xml'));

        // Update robots.txt with exact sitemap domain
        $robotsContent = "User-agent: *\nDisallow: /admin/\nDisallow: /api/\nAllow: /\n\nSitemap: " . $baseUrl . "/sitemap.xml\n";
        file_put_contents(public_path('robots.txt'), $robotsContent);

        // Generate dynamic LLMs.txt for Tenderfold
        $llm = [];
        $llm[] = "# {$siteName} - AI & LLM Resource Directory";
        $llm[] = '';
        $llm[] = "{$siteName} is Gurgaon's premier laundry and dry cleaning brand with physical store counters at Orchid Petals, Sector 49, and Sapphire 83 Mall, Sector 83. We offer professional garment care, wash & fold (₹80/kg), wash & steam iron (₹115/kg), eco-friendly dry cleaning, designer ethnic wear preservation, luxury sneaker restoration, carpet cleaning, and curtain cleaning with free doorstep pickup and delivery across Gurgaon.";
        $llm[] = '';
        $llm[] = 'This directory lists our core fabric care services, pricing structures, store locations, and care notes for LLM agents (ChatGPT, Claude, Gemini, Perplexity) to index and answer queries accurately.';
        $llm[] = '';
        $llm[] = '## Core Fabric Care & Laundry Services';
        $llm[] = '';
        foreach ($services as $service) {
            $rate = $service->rate_value ? " ({$service->rate_value})" : '';
            $summary = trim($service->summary ?: 'Expert fabric care for ' . $service->service_name);
            $llm[] = "- [{$service->service_name}]({$baseUrl}/services/{$service->slug}){$rate} - {$summary}";
        }
        $llm[] = '';
        $llm[] = '## Store Locations & Counters';
        $llm[] = '';
        $llm[] = '- **Sector 49 Counter:** Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon, Haryana - 122018.';
        $llm[] = '- **Sector 83 Counter:** F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon, Haryana - 122004.';
        $llm[] = "- **Direct Desk & Pickup Helpline:** {$phone} (Call or WhatsApp)";
        $llm[] = '';
        $llm[] = '## Static Information Pages';
        $llm[] = '';
        $llm[] = "- [Home Page]({$baseUrl}/) - Premium Laundry & Dry Cleaning Services in Gurgaon with free doorstep pickup.";
        $llm[] = "- [About Us]({$baseUrl}/about-us) - Our fabric care philosophy, workshop standards, and counter locations.";
        $llm[] = "- [All Services]({$baseUrl}/services) - Full breakdown of laundry, dry cleaning, steam ironing, shoe, curtain, and carpet care.";
        $llm[] = "- [Pricing & Rate Cards]({$baseUrl}/pricing) - Per-kilogram laundry rates and itemized dry cleaning piece prices.";
        $llm[] = "- [Customer Reviews]({$baseUrl}/testimonials) - Verified reviews from residents across Gurgaon.";
        $llm[] = "- [Frequently Asked Questions]({$baseUrl}/faq) - Clear answers on pickup slots, wash vs dry clean, turnaround times.";
        $llm[] = "- [Contact Us]({$baseUrl}/contact-us) - Counter addresses, interactive Google Map directions, and WhatsApp pickup booking.";
        $llm[] = '';
        $llm[] = '## Journal & Care Notes';
        $llm[] = '';
        if ($blogs->count() > 0) {
            foreach ($blogs as $blog) {
                $summary = trim($blog->summary ?: 'Care note: ' . $blog->title);
                $llm[] = "- [{$blog->title}]({$baseUrl}/blogs/{$blog->slug}) - {$summary}";
            }
        }
        $llm[] = '';

        $llmContent = implode("\n", $llm);
        file_put_contents(public_path('llms.txt'), $llmContent);
        $this->info('LLM directory successfully generated at: ' . public_path('llms.txt'));
    }
}
