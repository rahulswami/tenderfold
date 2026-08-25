<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileApiController;

Route::prefix('mobile')->group(function () {
    Route::get('/dashboard', [MobileApiController::class, 'dashboard']);
    Route::get('/blogs', [MobileApiController::class, 'getBlogs']);
    Route::post('/blogs/store', [MobileApiController::class, 'storeBlog']);
    Route::post('/social/generate-link', [MobileApiController::class, 'generateSocialLink']);
    Route::get('/social/links', [MobileApiController::class, 'getSocialLinks']);
    Route::get('/social/entities', [MobileApiController::class, 'getShareableEntities']);
    Route::get('/leads', [MobileApiController::class, 'getLeads']);
    Route::post('/leads/{id}/status', [MobileApiController::class, 'updateLeadStatus']);
});
