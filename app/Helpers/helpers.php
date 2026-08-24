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

if (!function_exists('parse_faqs')) {
    function parse_faqs($rawFaqs, $title = '') {
        $faqs = [];

        if (!empty($rawFaqs)) {
            if (is_array($rawFaqs)) {
                foreach ($rawFaqs as $item) {
                    if (is_array($item) && isset($item['question']) && isset($item['answer'])) {
                        $faqs[] = [
                            'question' => trim($item['question']),
                            'answer' => trim($item['answer'])
                        ];
                    }
                }
            } elseif (is_string($rawFaqs)) {
                $decoded = json_decode($rawFaqs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    foreach ($decoded as $item) {
                        if (is_array($item) && isset($item['question']) && isset($item['answer'])) {
                            $faqs[] = [
                                'question' => trim($item['question']),
                                'answer' => trim($item['answer'])
                            ];
                        }
                    }
                } else {
                    $lines = explode("\n", str_replace("\r", "", $rawFaqs));
                    foreach ($lines as $line) {
                        $parts = explode('|', $line, 2);
                        if (count($parts) === 2 && !empty(trim($parts[0])) && !empty(trim($parts[1]))) {
                            $faqs[] = [
                                'question' => trim($parts[0]),
                                'answer' => trim($parts[1])
                            ];
                        }
                    }
                }
            }
        }

        if (empty($faqs)) {
            $name = $title ?: 'treatment or service';
            $faqs = [
                [
                    'question' => "What should I expect during my consultation for {$name}?",
                    'answer' => "During your initial consultation at Dr. SumitZ Clinic, Dr. Sumit and our specialist team will conduct a comprehensive clinical examination, review diagnostic imaging (X-rays/MRIs), and discuss a tailored care plan specific to your symptoms."
                ],
                [
                    'question' => "Is an appointment required for {$name}?",
                    'answer' => "While we accommodate walk-ins when possible, booking an advance appointment is recommended to minimize wait times and ensure dedicated time with our orthopaedic and physiotherapy team in Baner and Balewadi."
                ],
                [
                    'question' => "What is the expected recovery timeline for {$name}?",
                    'answer' => "Recovery times vary based on severity, health condition, and treatment type. Non-invasive physical therapy often shows improvements in 2-4 weeks, while surgical procedures involve structured rehabilitation programs over 6-12 weeks."
                ],
                [
                    'question' => "Do you offer diagnostic imaging and physiotherapy at your Baner clinic?",
                    'answer' => "Yes, Dr. SumitZ Clinic is a complete multidisciplinary center equipped with digital X-Ray facilities, BMD bone density scans, and a state-of-the-art physiotherapy setup for complete diagnosis and rehabilitation under one roof."
                ]
            ];
        }

        return $faqs;
    }
}
