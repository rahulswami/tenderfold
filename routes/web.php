<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Public Pages
// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{slug}', [HomeController::class, 'servicesShow'])->name('services.show');
Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/blogs', [HomeController::class, 'blog'])->name('blog');
Route::get('/blogs/{slug}', [HomeController::class, 'blogShow'])->name('blog.show');
Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact');
Route::get('/thank-you', [HomeController::class, 'thankYou'])->name('thank-you');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/l/{slug}', [HomeController::class, 'landingShow'])->name('landing.show');

// Clinic Treatments
Route::get('/treatments', [HomeController::class, 'treatments'])->name('treatments');
Route::get('/treatments/{slug}', [HomeController::class, 'treatmentDetail'])->name('treatments.show');

// Clinic Specialists
Route::get('/specialists', [HomeController::class, 'specialists'])->name('specialists');
Route::get('/specialists/{slug}', [HomeController::class, 'specialistdetails'])->name('specialists.show');

// 301 Redirects for Old SEO URLs
Route::redirect('/blog', '/blogs', 301);
Route::redirect('/blog/{slug}', '/blogs/{slug}', 301);
Route::redirect('/about', '/about-us', 301);
Route::redirect('/contact', '/contact-us', 301);
Route::redirect('/faqs', '/faq', 301);
Route::redirect('/facilities', '/', 301); // Redirect facilities to home page if not explicitly managed

// Loan Specific Route (Kept for compatibility, but styled for clinic context if visited)
Route::redirect('/compare-loans', '/', 301);
Route::post('/api/database/import', [AdminController::class, 'importLiveDatabase']);

// Live server DB setup — creates all missing tables in one request
Route::get('/setup-database', [AdminController::class, 'setupLiveDatabase']);

// Admin CMS Auth
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin CMS Dashboard (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/parameters', [AdminController::class, 'updateParameters'])->name('admin.parameters.update');
    
    Route::post('/admin/slides', [AdminController::class, 'storeSlide'])->name('admin.slides.store');
    Route::post('/admin/slides/{id}/update', [AdminController::class, 'updateSlide'])->name('admin.slides.update');
    Route::post('/admin/slides/{id}/delete', [AdminController::class, 'deleteSlide'])->name('admin.slides.delete');
    
    Route::post('/admin/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::post('/admin/testimonials/{id}/update', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::post('/admin/testimonials/{id}/delete', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.delete');

    // Blogs CRUD
    Route::get('/admin/blogs/list', [AdminController::class, 'listBlogs'])->name('admin.blogs.list');
    Route::get('/admin/blogs/{id}/detail', [AdminController::class, 'detailBlog'])->name('admin.blogs.detail');
    Route::post('/admin/blogs', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
    Route::post('/admin/blogs/{id}/update', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
    Route::post('/admin/blogs/{id}/delete', [AdminController::class, 'deleteBlog'])->name('admin.blogs.delete');

    // Services CRUD
    Route::post('/admin/services', [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::post('/admin/services/{id}/update', [AdminController::class, 'updateService'])->name('admin.services.update');
    Route::post('/admin/services/{id}/delete', [AdminController::class, 'deleteService'])->name('admin.services.delete');

    // Treatments CRUD
    Route::post('/admin/treatments', [AdminController::class, 'storeTreatment'])->name('admin.treatments.store');
    Route::post('/admin/treatments/{id}/update', [AdminController::class, 'updateTreatment'])->name('admin.treatments.update');
    Route::post('/admin/treatments/{id}/delete', [AdminController::class, 'deleteTreatment'])->name('admin.treatments.delete');

    // Specialists CRUD
    Route::post('/admin/specialists', [AdminController::class, 'storeSpecialist'])->name('admin.specialists.store');
    Route::post('/admin/specialists/{id}/update', [AdminController::class, 'updateSpecialist'])->name('admin.specialists.update');
    Route::post('/admin/specialists/{id}/delete', [AdminController::class, 'deleteSpecialist'])->name('admin.specialists.delete');

    // CMS Pages CRUD
    Route::post('/admin/cms-pages', [AdminController::class, 'storeCmsPage'])->name('admin.cms_pages.store');
    Route::post('/admin/cms-pages/{id}/update', [AdminController::class, 'updateCmsPage'])->name('admin.cms_pages.update');
    Route::post('/admin/cms-pages/{id}/delete', [AdminController::class, 'deleteCmsPage'])->name('admin.cms_pages.delete');

    // Banks CRUD
    Route::post('/admin/banks', [AdminController::class, 'storeBank'])->name('admin.banks.store');
    Route::post('/admin/banks/{id}/update', [AdminController::class, 'updateBank'])->name('admin.banks.update');
    Route::post('/admin/banks/{id}/delete', [AdminController::class, 'deleteBank'])->name('admin.banks.delete');

    // Page Content Update
    Route::post('/admin/page-contents', [AdminController::class, 'updatePageContents'])->name('admin.page-contents.update');

    // Media Manager Upload
    Route::post('/admin/media-upload', [AdminController::class, 'uploadMedia'])->name('admin.media.upload');
    Route::post('/admin/media/sync-r2', [AdminController::class, 'syncLocalImagesToR2'])->name('admin.media.sync');
    Route::post('/admin/database/push-to-live', [AdminController::class, 'pushLocalDatabaseToLive'])->name('admin.database.push-to-live');

    // Sync database content
    Route::post('/admin/sync-database', [AdminController::class, 'syncDatabase'])->name('admin.sync-database');

    // Leads Management
    Route::post('/admin/leads/{id}/update', [AdminController::class, 'updateLead'])->name('admin.leads.update');
    Route::post('/admin/leads/{id}/delete', [AdminController::class, 'deleteLead'])->name('admin.leads.delete');

    // AI Content Generation
    Route::post('/admin/ai/generate-blog', [AdminController::class, 'aiGenerateBlog'])->name('admin.ai.blog');
    Route::post('/admin/ai/generate-service', [AdminController::class, 'aiGenerateService'])->name('admin.ai.service');
    Route::post('/admin/ai/generate-image', [AdminController::class, 'aiGenerateImage'])->name('admin.ai.image');
    Route::post('/admin/ai/seo-recommendations', [AdminController::class, 'aiGenerateSeoRecommendations'])->name('admin.ai.seo-recommendations');
    Route::post('/admin/ai/generate-schema', [AdminController::class, 'aiGenerateSchema'])->name('admin.ai.schema.generate');
    Route::post('/admin/schema/update', [AdminController::class, 'saveSchemaMarkup'])->name('admin.schema.update');

    // Cloudflare Analytics
    Route::post('/admin/cloudflare/analytics', [AdminController::class, 'getCloudflareAnalytics'])->name('admin.cloudflare.analytics');

    // Landing Pages CRUD
    Route::post('/admin/landing-pages', [AdminController::class, 'storeLandingPage'])->name('admin.landing.store');
    Route::post('/admin/landing-pages/{id}/update', [AdminController::class, 'updateLandingPage'])->name('admin.landing.update');
    Route::post('/admin/landing-pages/{id}/delete', [AdminController::class, 'deleteLandingPage'])->name('admin.landing.delete');
    Route::post('/admin/ai/generate-landing', [AdminController::class, 'aiGenerateLandingPage'])->name('admin.ai.landing');
    Route::post('/admin/ai/sync-gmb-testimonials', [AdminController::class, 'aiSyncGmbTestimonials'])->name('admin.ai.gmb-sync');

    // GMB Sharing & Sitemap Routes
    Route::post('/admin/sitemap/generate', [AdminController::class, 'generateSitemap'])->name('admin.sitemap.generate');
    Route::post('/admin/ai/draft-gmb-post', [AdminController::class, 'aiDraftGmbPost'])->name('admin.ai.gmb-draft');
    Route::post('/admin/gmb/publish-post', [AdminController::class, 'publishGmbPost'])->name('admin.gmb.publish');
});

// CMS catch-all route (placed at the end to prevent conflicts)
Route::get('/{slug}', [HomeController::class, 'cmsPageShow'])->name('cms.show');
