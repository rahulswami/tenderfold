<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteParameter;
use App\Models\PageContent;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\TeamMember;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Share safe default empty collections/arrays first so views never crash on null
        View::share('site', []);
        View::share('pageContents', []);
        View::share('globalServices', collect());
        View::share('globalTreatments', collect());
        View::share('globalSpecialists', collect());

        // Skip heavy view sharing database queries for CLI, API, AJAX or JSON requests to conserve connections
        if ($this->app->runningInConsole() || request()->is('api/*') || request()->is('chat/*') || request()->ajax() || request()->wantsJson()) {
            return;
        }

        try {
            $site = Cache::remember('global_site_parameters', 3600, function () {
                if (Schema::hasTable('site_parameters')) {
                    return SiteParameter::all()->pluck('value', 'id')->toArray();
                }
                return [];
            });
            View::share('site', $site);
        } catch (\Throwable $e) {
            View::share('site', []);
        }

        try {
            $pageContents = Cache::remember('global_page_contents', 3600, function () {
                if (Schema::hasTable('page_contents')) {
                    $contents = PageContent::all();
                    $result = [];
                    foreach ($contents as $content) {
                        $result[$content->page][$content->section][$content->key] = $content->value;
                    }
                    return $result;
                }
                return [];
            });
            View::share('pageContents', $pageContents);
        } catch (\Throwable $e) {
            View::share('pageContents', []);
        }

        try {
            if (Schema::hasTable('services')) {
                $globalServices = Service::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
                View::share('globalServices', $globalServices);
            }
        } catch (\Throwable $e) {
            View::share('globalServices', collect());
        }

        try {
            if (Schema::hasTable('treatments')) {
                $globalTreatments = Treatment::where('status', true)->orderBy('id')->get();
                View::share('globalTreatments', $globalTreatments);
            }
        } catch (\Throwable $e) {
            View::share('globalTreatments', collect());
        }

        try {
            if (Schema::hasTable('team_members')) {
                $globalSpecialists = TeamMember::where('status', true)->orderBy('id')->get();
                View::share('globalSpecialists', $globalSpecialists);
            }
        } catch (\Throwable $e) {
            View::share('globalSpecialists', collect());
        }
    }
}
