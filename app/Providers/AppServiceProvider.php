<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (\Illuminate\Support\Facades\Schema::hasTable('site_parameters')) {
            try {
                $parameters = \App\Models\SiteParameter::all()->pluck('value', 'id')->toArray();
                \Illuminate\Support\Facades\View::share('site', $parameters);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('site', []);
            }
        } else {
            \Illuminate\Support\Facades\View::share('site', []);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('page_contents')) {
            try {
                $contents = \App\Models\PageContent::all();
                $pageContents = [];
                foreach ($contents as $content) {
                    $pageContents[$content->page][$content->section][$content->key] = $content->value;
                }
                \Illuminate\Support\Facades\View::share('pageContents', $pageContents);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('pageContents', []);
            }
        } else {
            \Illuminate\Support\Facades\View::share('pageContents', []);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('services')) {
            try {
                $globalServices = \App\Models\Service::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
                \Illuminate\Support\Facades\View::share('globalServices', $globalServices);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('globalServices', collect());
            }
        } else {
            \Illuminate\Support\Facades\View::share('globalServices', collect());
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('treatments')) {
            try {
                $globalTreatments = \App\Models\Treatment::where('status', true)->orderBy('id')->get();
                \Illuminate\Support\Facades\View::share('globalTreatments', $globalTreatments);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('globalTreatments', collect());
            }
        } else {
            \Illuminate\Support\Facades\View::share('globalTreatments', collect());
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('team_members')) {
            try {
                $globalSpecialists = \App\Models\TeamMember::where('status', true)->orderBy('id')->get();
                \Illuminate\Support\Facades\View::share('globalSpecialists', $globalSpecialists);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('globalSpecialists', collect());
            }
        } else {
            \Illuminate\Support\Facades\View::share('globalSpecialists', collect());
        }
    }
}
