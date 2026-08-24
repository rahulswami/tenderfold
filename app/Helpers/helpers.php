<?php

if (!function_exists('site_image')) {
    function site_image($path, $fallbackType = 'general') {
        if (empty($path)) {
            return default_fallback_image($fallbackType);
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        // 1. Direct file check in public/
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        // 2. Check in uploads subdirectories
        $uploadFolders = ['', 'category/', 'product/', 'gallery/', 'page/', 'collection/', 'feedback/', 'profile/', 'lookbook/'];
        
        $filename = str_starts_with($cleanPath, 'uploads/') ? substr($cleanPath, 8) : $cleanPath;

        foreach ($uploadFolders as $folder) {
            $testPath = 'uploads/' . $folder . $filename;
            if (file_exists(public_path($testPath))) {
                return asset($testPath);
            }
        }

        // 3. Storage directory check
        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        // 4. Default fallback if image file is missing
        return default_fallback_image($fallbackType);
    }
}

if (!function_exists('default_fallback_image')) {
    function default_fallback_image($type = 'general') {
        switch ($type) {
            case 'service':
                return 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80';
            case 'treatment':
                return 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80';
            case 'blog':
                return 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=800&q=80';
            case 'specialist':
            case 'doctor':
                return 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80';
            case 'clinic':
                return 'https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?auto=format&fit=crop&w=1200&q=80';
            default:
                return 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80';
        }
    }
}
