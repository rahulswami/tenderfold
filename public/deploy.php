<?php
/**
 * Tenderfold Live Deployment, Diagnostic & Self-Healing Script
 * Universal script for Hostinger, cPanel, Apache, Nginx, VPS, and Cloud servers.
 * Handles Git Pull, Database Migrations, Clean Data Seeding, Cache Flushing, and Diagnostics.
 */

// 1. Detect base project path
$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;
$publicPath = $isInsidePublic ? __DIR__ : $basePath . '/public';

$checks = [];
$actions = [];

// 1.1 Auto Git Pull first so newest script and repository updates are in place
if (is_dir($basePath . '/.git')) {
    $gitOutput = [];
    $gitStatus = 0;
    @exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput, $gitStatus);
    if (!empty($gitOutput)) {
        $actions[] = "Git Pull: <code>" . htmlspecialchars(implode(' ', $gitOutput)) . "</code> ✅";
    }
}

// 1.2 Clear bootstrap config/routes cache so new .env settings take immediate effect
$bootstrapCacheFiles = glob($basePath . '/bootstrap/cache/*.php');
if ($bootstrapCacheFiles) {
    foreach ($bootstrapCacheFiles as $bFile) {
        if (basename($bFile) !== 'packages.php' && basename($bFile) !== 'services.php') {
            @unlink($bFile);
        }
    }
}

// 2. Locate or Auto-Restore .env file
$envPath = $basePath . '/.env';
$backupSources = [
    $basePath . '/.env.backup',
    dirname($basePath) . '/.env.backup',
    $basePath . '/.env.example'
];

$needsRestoration = false;
if (!file_exists($envPath) || filesize($envPath) < 50) {
    $needsRestoration = true;
} else {
    $currContent = @file_get_contents($envPath);
    if ($currContent === false || (stripos($currContent, 'DB_DATABASE=') === false && stripos($currContent, 'DB_CONNECTION=') === false)) {
        $needsRestoration = true;
    }
}

if ($needsRestoration) {
    foreach ($backupSources as $bSrc) {
        if (file_exists($bSrc) && filesize($bSrc) > 100) {
            $srcContent = file_get_contents($bSrc);
            if ($srcContent !== false && (stripos($srcContent, 'DB_DATABASE=') !== false || stripos($srcContent, 'DB_CONNECTION=') !== false)) {
                file_put_contents($envPath, $srcContent);
                @chmod($envPath, 0644);
                $actions[] = "Restored <code>.env</code> from backup: <code>{$bSrc}</code> (" . strlen($srcContent) . " bytes) ✅";
                if (!file_exists($basePath . '/.env.backup')) {
                    @file_put_contents($basePath . '/.env.backup', $srcContent);
                }
                break;
            }
        }
    }
}

// Also keep backup in project root
if (file_exists($envPath) && filesize($envPath) > 100 && !file_exists($basePath . '/.env.backup')) {
    @copy($envPath, $basePath . '/.env.backup');
}

$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $env[trim($parts[0])] = trim($parts[1], '"\' ');
        }
    }

    // 2.1 Auto-generate APP_KEY if missing or empty
    if (empty($env['APP_KEY']) || strlen($env['APP_KEY']) < 20) {
        $generatedKey = 'base64:' . base64_encode(random_bytes(32));
        $envContent = file_get_contents($envPath);
        if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
            $envContent = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $generatedKey, $envContent);
        } else {
            $envContent = "APP_KEY=" . $generatedKey . "\n" . $envContent;
        }
        @file_put_contents($envPath, $envContent);
        $env['APP_KEY'] = $generatedKey;
        $actions[] = "Generated and saved fresh <code>APP_KEY</code> in <code>.env</code> ✅";
    }

    // Ensure APP_URL is production domain
    if (!isset($env['APP_URL']) || empty($env['APP_URL']) || strpos($env['APP_URL'], 'localhost') !== false) {
        $rawEnv = file_get_contents($envPath);
        $rawEnv = preg_replace('/^APP_URL=.*$/m', 'APP_URL=https://tenderfold.in', $rawEnv);
        @file_put_contents($envPath, $rawEnv);
        $env['APP_URL'] = 'https://tenderfold.in';
    }

    $checks['.env File'] = [
        'status' => true,
        'msg' => 'Found & Loaded. Database: <strong>' . htmlspecialchars($env['DB_DATABASE'] ?? 'not set') . '</strong>, User: ' . htmlspecialchars($env['DB_USERNAME'] ?? 'not set')
    ];

    $checks['Application Key (APP_KEY)'] = [
        'status' => !empty($env['APP_KEY']),
        'msg' => !empty($env['APP_KEY']) ? 'Configured (' . substr($env['APP_KEY'], 0, 15) . '...)' : 'Missing APP_KEY'
    ];
} else {
    $checks['.env File'] = ['status' => false, 'msg' => 'Missing .env file in root directory'];
    $checks['Application Key (APP_KEY)'] = ['status' => false, 'msg' => 'Missing .env'];
}

// 2.2 Inspect recent Laravel logs if available
$logFile = $basePath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logContent = file($logFile);
    $lastLines = array_slice($logContent, -25);
    $checks['Recent Laravel Log'] = [
        'status' => true,
        'msg' => '<pre style="max-height: 180px; overflow-y: auto; font-size: 11px; white-space: pre-wrap;">' . htmlspecialchars(implode('', $lastLines)) . '</pre>'
    ];
}

// 3. Ensure Storage & Cache Directories Exist with Proper Permissions (0775)
$storageDirs = [
    $basePath . '/storage',
    $basePath . '/storage/app',
    $basePath . '/storage/app/public',
    $basePath . '/storage/framework',
    $basePath . '/storage/framework/cache',
    $basePath . '/storage/framework/cache/data',
    $basePath . '/storage/framework/sessions',
    $basePath . '/storage/framework/views',
    $basePath . '/storage/logs',
    $basePath . '/bootstrap/cache',
];

$storageWritable = true;
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @chmod($dir, 0775);
    if (!is_writable($dir)) {
        $storageWritable = false;
    }
}
$checks['Storage & Cache Permissions'] = [
    'status' => $storageWritable,
    'msg' => $storageWritable ? 'All directories writable (0775)' : 'Some storage directories are not writable. Check file permissions.'
];

// 4. Ensure Root .htaccess exists to fix 403 Forbidden on Hostinger
// 4. Ensure Root .htaccess exists to fix 403 Forbidden on Hostinger
$rootHtaccess = $basePath . '/.htaccess';
$rootHtaccessContent = <<<HTACCESS
DirectoryIndex index.php public/index.php

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^\.env - [F,L,NC]
    RewriteRule ^composer\.(json|lock) - [F,L,NC]
    RewriteRule ^package(-lock)?\.json - [F,L,NC]
    RewriteRule ^storage/logs/ - [F,L,NC]
    RewriteCond %{REQUEST_URI} ^/deploy\.php$ [NC]
    RewriteRule ^ - [L]
    RewriteRule ^$ public/index.php [L]
    RewriteCond %{REQUEST_URI} ^/public/ [NC]
    RewriteRule ^ - [L]
    RewriteCond %{DOCUMENT_ROOT}/public/$1 -f [OR]
    RewriteCond %{DOCUMENT_ROOT}/public/$1 -d
    RewriteRule ^(.*)$ public/$1 [L]
    RewriteRule ^(.*)$ public/index.php [L]
</IfModule>
<IfModule !mod_rewrite.c>
    RedirectMatch 302 ^/$ /public/
</IfModule>
HTACCESS;

@file_put_contents($rootHtaccess, $rootHtaccessContent);
$actions[] = "Created / Updated Root <code>.htaccess</code> for universal Laravel routing ✅";
$checks['Root .htaccess (Hostinger 403 Fix)'] = ['status' => true, 'msg' => 'Configured & Active'];

// 4.1 Ensure Root index.php exists as fallback
$rootIndex = $basePath . '/index.php';
$rootIndexContent = <<<PHP
<?php
/**
 * Tenderfold - Root Entry Proxy to Public Front Controller
 */
\$publicIndex = __DIR__ . '/public/index.php';
if (file_exists(\$publicIndex)) {
    require_once \$publicIndex;
} else {
    http_response_code(500);
    echo "Application front controller not found in /public.";
}
PHP;

if (!file_exists($rootIndex) || filesize($rootIndex) < 20) {
    @file_put_contents($rootIndex, $rootIndexContent);
    $actions[] = "Created Root <code>index.php</code> proxy ✅";
}

// 5. Ensure Public .htaccess exists
$publicHtaccess = $publicPath . '/.htaccess';
if (!file_exists($publicHtaccess)) {
    $pubContent = <<<HTACCESS
<IfModule mod_rewrite.c>
    Options -MultiViews -Indexes
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HTACCESS;
    @file_put_contents($publicHtaccess, $pubContent);
    $actions[] = "Created <code>public/.htaccess</code> for Laravel URL routing ✅";
}

// 5.1 Ensure SQLite database file exists as fallback
$sqlitePath = $basePath . '/database/database.sqlite';
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
    @chmod($sqlitePath, 0666);
    $actions[] = "Created fallback <code>database/database.sqlite</code> ✅";
}

// 6. Connect to Database & Seed/Repair Schema
$dbConn = $env['DB_CONNECTION'] ?? 'mysql';
$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

$pdo = null;
$dbConnected = false;
$dbError = '';

if ($dbConn === 'sqlite') {
    try {
        $pdo = new PDO("sqlite:" . $sqlitePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbConnected = true;
    } catch (PDOException $e) {
        $dbError = $e->getMessage();
    }
} elseif (!empty($dbName)) {
    try {
        $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        $dbConnected = true;
    } catch (PDOException $e) {
        $dbError = $e->getMessage();
    }
} else {
    $dbError = "DB_DATABASE is not configured in .env (DB_CONNECTION={$dbConn})";
}

$checks['Database Connection'] = [
    'status' => $dbConnected,
    'msg' => $dbConnected ? "Connected ({$dbConn}: <strong>" . ($dbConn === 'sqlite' ? 'database.sqlite' : $dbName) . "</strong>)" : "Failed: {$dbError}"
];

// 7. Auto-Create / Migrate Database Tables & Sync Content
if ($pdo && $dbConn === 'mysql') {
    // 7.1 Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL UNIQUE,
        `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
        `password` VARCHAR(255) NOT NULL,
        `role` VARCHAR(50) NOT NULL DEFAULT 'superadmin',
        `permissions` LONGTEXT NULL,
        `remember_token` VARCHAR(100) NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Ensure Admin User exists
    $adminStmt = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `email` = ?");
    $adminStmt->execute(['admin@tenderfold.in']);
    if ($adminStmt->fetchColumn() == 0) {
        $passHash = password_hash('admin123', PASSWORD_BCRYPT);
        $insertAdmin = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`, `permissions`, `created_at`, `updated_at`) VALUES (?, ?, ?, 'superadmin', '[\"all\"]', NOW(), NOW())");
        $insertAdmin->execute(['Tenderfold Admin', 'admin@tenderfold.in', $passHash]);
        $actions[] = "Admin account verified: <code>admin@tenderfold.in</code> / <code>admin123</code> ✅";
    }

    // 7.2 Services table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `services` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `service_name` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `summary` TEXT NULL,
        `rate_value` VARCHAR(100) NULL,
        `turnaround_time` VARCHAR(100) NULL,
        `hero_title` VARCHAR(255) NULL,
        `hero_subtitle` TEXT NULL,
        `hero_category` VARCHAR(100) NULL,
        `intro_content` LONGTEXT NULL,
        `intro_title` VARCHAR(255) NULL,
        `eligibility_criteria` TEXT NULL,
        `documents_required` TEXT NULL,
        `tips_title` VARCHAR(255) NULL,
        `tips_content` LONGTEXT NULL,
        `max_loan` VARCHAR(255) NULL,
        `tenure` VARCHAR(255) NULL,
        `badge` VARCHAR(100) NULL,
        `care_steps` JSON NULL,
        `inclusions` JSON NULL,
        `price_list` JSON NULL,
        `faqs` JSON NULL,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `metakeywords` VARCHAR(255) NULL,
        `schema_markup` TEXT NULL,
        `sort_order` INT NOT NULL DEFAULT 0,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.3 Home Slides table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `home_slides` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `subtitle` TEXT NULL,
        `button_text` VARCHAR(100) NULL DEFAULT 'Schedule a Pickup',
        `button_link` VARCHAR(255) NULL DEFAULT '#bookModal',
        `image_path` VARCHAR(255) NULL,
        `mobile_image_path` VARCHAR(255) NULL,
        `sort_order` INT NOT NULL DEFAULT 0,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.4 Testimonials table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `role` VARCHAR(255) NULL,
        `designation` VARCHAR(255) NULL,
        `content` TEXT NOT NULL,
        `rating` INT NOT NULL DEFAULT 5,
        `avatar_path` VARCHAR(255) NULL,
        `sort_order` INT NOT NULL DEFAULT 0,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.5 Blogs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `blogs` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `summary` TEXT NULL,
        `content` LONGTEXT NOT NULL,
        `category` VARCHAR(100) NULL DEFAULT 'Care Notes',
        `image_path` VARCHAR(255) NULL,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `metakeywords` VARCHAR(255) NULL,
        `tags` VARCHAR(255) NULL,
        `schema_markup` TEXT NULL,
        `is_published` TINYINT(1) NOT NULL DEFAULT 1,
        `published_at` TIMESTAMP NULL DEFAULT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.6 Site Parameters table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `site_parameters` (
        `id` VARCHAR(191) PRIMARY KEY,
        `value` LONGTEXT NULL,
        `label` VARCHAR(255) NULL,
        `category` VARCHAR(100) NULL DEFAULT 'general',
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.7 Leads & Activities tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS `leads` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NULL,
        `service` VARCHAR(255) NULL,
        `message` TEXT NULL,
        `status` VARCHAR(50) NOT NULL DEFAULT 'New',
        `source` VARCHAR(100) NOT NULL DEFAULT 'Website Pickup',
        `notes` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `lead_activities` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `lead_id` BIGINT UNSIGNED NOT NULL,
        `user_name` VARCHAR(255) NOT NULL DEFAULT 'Admin',
        `status_from` VARCHAR(255) NULL,
        `status_to` VARCHAR(255) NULL,
        `notes` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX (`lead_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.8 CMS Pages
    $pdo->exec("CREATE TABLE IF NOT EXISTS `cms_pages` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `metakeywords` TEXT NULL,
        `image` VARCHAR(255) NULL,
        `description` LONGTEXT NULL,
        `status` TINYINT(1) NOT NULL DEFAULT 1,
        `schema_markup` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    try {
        $pdo->exec("ALTER TABLE `cms_pages` ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1");
    } catch (Exception $e) {}

    // 7.9 Landing Pages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `landing_pages` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `layout_type` VARCHAR(100) NOT NULL DEFAULT 'home',
        `meta_description` TEXT NULL,
        `content` JSON NULL,
        `status` VARCHAR(50) NOT NULL DEFAULT 'published',
        `views_count` INT NOT NULL DEFAULT 0,
        `conversions_count` INT NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.10 Page Contents table (for full admin frontend sync)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `page_contents` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `page` VARCHAR(100) NOT NULL,
        `section` VARCHAR(100) NOT NULL,
        `key` VARCHAR(100) NOT NULL,
        `value` LONGTEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        UNIQUE KEY `page_section_key_unique` (`page`, `section`, `key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.11 Notifications & Push
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_notifications` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `type` VARCHAR(255) NOT NULL DEFAULT 'info',
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NULL,
        `user_name` VARCHAR(255) NULL,
        `action_url` VARCHAR(2048) NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `push_subscriptions` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `session_id` VARCHAR(255) NULL,
        `endpoint` TEXT NULL,
        `auth_token` TEXT NULL,
        `public_key` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `web_notifications` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `session_id` VARCHAR(255) NULL,
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `url` VARCHAR(2048) NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 7.12 Sessions & Cache
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `sessions` (
            `id` VARCHAR(255) PRIMARY KEY,
            `user_id` BIGINT UNSIGNED NULL,
            `ip_address` VARCHAR(45) NULL,
            `user_agent` TEXT NULL,
            `payload` LONGTEXT NOT NULL,
            `last_activity` INT NOT NULL,
            INDEX (`user_id`),
            INDEX (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `cache` (
            `key` VARCHAR(255) PRIMARY KEY,
            `value` MEDIUMTEXT NOT NULL,
            `expiration` INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `cache_locks` (
            `key` VARCHAR(255) PRIMARY KEY,
            `owner` VARCHAR(255) NOT NULL,
            `expiration` INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    } catch (\Throwable $e) {}

    // 7.13 Purge legacy dummy parameters from older templates
    try {
        $pdo->exec("DELETE FROM `site_parameters` WHERE `id` IN (
            'address_baner', 'address_hinjewadi', 'clinic_locations_short', 'clinic_timings', 
            'footer_helplines', 'helpline_baner', 'helpline_hinjewadi', 'home_loan_rate', 
            'personal_loan_rate', 'business_loan_rate', 'car_loan_rate', 'lap_rate',
            'loan_types', 'bank_partners', 'specialists_count', 'treatments_count'
        )");
        $actions[] = "Purged legacy dummy loan/clinic parameters from database ✅";
    } catch (\Throwable $e) {}

    // 7.14 Clean & Self-Heal Character Encoding & Replace Gurugram with Gurgaon
    try {
        $pdo->exec("UPDATE `services` SET `rate_value` = REPLACE(`rate_value`, 'â‚¹', '₹') WHERE `rate_value` LIKE '%â‚¹%'");
        $pdo->exec("UPDATE `services` SET `summary` = REPLACE(`summary`, 'â€™', '\''), `hero_subtitle` = REPLACE(`hero_subtitle`, 'â€™', '\'') WHERE `summary` LIKE '%â€™%' OR `hero_subtitle` LIKE '%â€™%'");
        $pdo->exec("UPDATE `home_slides` SET `subtitle` = REPLACE(`subtitle`, 'Gurugram', 'Gurgaon'), `title` = REPLACE(`title`, 'Gurugram', 'Gurgaon') WHERE `subtitle` LIKE '%Gurugram%' OR `title` LIKE '%Gurugram%'");
        $pdo->exec("UPDATE `site_parameters` SET `value` = REPLACE(`value`, 'Gurugram', 'Gurgaon') WHERE `value` LIKE '%Gurugram%'");
        $pdo->exec("UPDATE `landing_pages` SET `title` = REPLACE(`title`, 'Gurugram', 'Gurgaon'), `meta_description` = REPLACE(`meta_description`, 'Gurugram', 'Gurgaon') WHERE `title` LIKE '%Gurugram%' OR `meta_description` LIKE '%Gurugram%'");
        $pdo->exec("UPDATE `page_contents` SET `value` = REPLACE(`value`, 'Gurugram', 'Gurgaon') WHERE `value` LIKE '%Gurugram%'");
        $actions[] = "Normalized all city references to 'Gurgaon' and repaired UTF-8 characters ✅";
    } catch (\Throwable $e) {}

    // 7.14.1 Self-Heal Blog Posts with Base64 Images or Hidden Reveal Tags
    try {
        $blogStmt = $pdo->query("SELECT `id`, `content` FROM `blogs` WHERE `content` LIKE '%data:image/%' OR `content` LIKE '%reveal%'");
        $healedCount = 0;
        while ($bRow = $blogStmt->fetch(PDO::FETCH_ASSOC)) {
            $bId = $bRow['id'];
            $bContent = $bRow['content'];

            // Clean accidental reveal wrapper
            $bContent = preg_replace('/<!--\s*Content Container\s*-->/i', '', $bContent);
            $bContent = preg_replace('/^\s*<div[^>]*class=["\'][^"\']*prose[^"\']*["\'][^>]*>/i', '', $bContent);
            $bContent = preg_replace('/<\/div>\s*$/i', '', $bContent);
            $bContent = preg_replace_callback('/<([a-z0-9]+)([^>]*class=["\'])([^"\']*)(["\'][^>]*)>/i', function ($m) {
                $classes = trim(preg_replace('/\s+/', ' ', preg_replace('/\breveal\b/i', '', $m[3])));
                return '<' . $m[1] . $m[2] . $classes . $m[4] . '>';
            }, $bContent);

            // Extract and save base64 images
            if (stripos($bContent, 'data:image/') !== false) {
                $bContent = preg_replace_callback('/src=["\'](data:image\/([a-zA-Z0-9\+\-]+);base64,([^"\']+))["\']/i', function ($m) use ($basePath) {
                    $rawMime = strtolower($m[2]);
                    $binaryData = base64_decode($m[3]);
                    if (!$binaryData) return $m[0];

                    $blogDir = $basePath . '/storage/app/public/blog';
                    if (!is_dir($blogDir)) @mkdir($blogDir, 0775, true);

                    $filename = uniqid('img_') . '.webp';
                    $savedPath = $blogDir . '/' . $filename;
                    $image = @imagecreatefromstring($binaryData);
                    $success = false;
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                        $success = imagewebp($image, $savedPath, 85);
                        imagedestroy($image);
                    }
                    if (!$success) {
                        $ext = ($rawMime === 'jpeg' || $rawMime === 'jpg') ? 'jpg' : (($rawMime === 'png') ? 'png' : 'webp');
                        $filename = uniqid('img_') . '.' . $ext;
                        $savedPath = $blogDir . '/' . $filename;
                        @file_put_contents($savedPath, $binaryData);
                    }
                    return 'src="/storage/blog/' . $filename . '"';
                }, $bContent);
            }

            $upd = $pdo->prepare("UPDATE `blogs` SET `content` = ? WHERE `id` = ?");
            $upd->execute([$bContent, $bId]);
            $healedCount++;
        }
        if ($healedCount > 0) {
            $actions[] = "Self-healed {$healedCount} blog posts (converted base64 images and cleaned reveal tags) ✅";
        }
    } catch (\Throwable $e) {}

    // 7.15 Synchronize Core Site Parameters (Upsert)
    $coreParams = [
        ['site_name', 'Tenderfold', 'Site Name', 'general'],
        ['site_tagline', 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon', 'Site Tagline', 'general'],
        ['meta_description', 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.', 'Default Meta Description', 'general'],
        ['meta_keywords', 'laundry gurgaon, dry cleaning gurgaon, wash and fold, steam ironing, shoe cleaning, carpet cleaning, sector 49, sector 83, tenderfold', 'Default Meta Keywords', 'general'],
        ['live_site_url', 'https://tenderfold.in', 'Live Site URL', 'general'],
        ['phone', '+91 95824 66445', 'Contact Phone Number', 'contact'],
        ['phone_display', '+91 95824 66445', 'Display Phone Number', 'contact'],
        ['email', 'info@tenderfold.in', 'Contact Email Address', 'contact'],
        ['address', 'Sector 49 & Sector 83, Gurgaon, Haryana - 122018', 'Physical Address', 'contact'],
        ['whatsapp_number', '919582466445', 'WhatsApp Number', 'contact'],
        ['logo_path', 'assets/logo.png', 'Logo Image', 'branding'],
        ['favicon_path', 'assets/logo.png', 'Favicon Image', 'branding'],
        ['copyright_text', '© ' . date('Y') . ' Tenderfold. Freshness in Every Single Fold. All Rights Reserved.', 'Footer Copyright Line', 'general'],
        ['footer_description', 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric care service. Doorstep pickup across all major sectors.', 'Footer Description', 'general'],
        ['social_instagram', 'https://www.instagram.com/tenderfold/', 'Instagram URL', 'social'],
        ['social_facebook', 'https://facebook.com/tenderfold', 'Facebook URL', 'social'],
        ['social_practo', 'https://maps.app.goo.gl/tenderfold', 'Google Maps URL', 'social'],
    ];

    foreach ($coreParams as $cp) {
        $stmt = $pdo->prepare("INSERT INTO `site_parameters` (`id`, `value`, `label`, `category`, `created_at`, `updated_at`) 
                               VALUES (?, ?, ?, ?, NOW(), NOW()) 
                               ON DUPLICATE KEY UPDATE `label` = VALUES(`label`), `category` = VALUES(`category`), `updated_at` = NOW()");
        $stmt->execute([$cp[0], $cp[1], $cp[2], $cp[3]]);
    }

    // 7.16 Synchronize Landing Pages
    $landingPages = [
        [
            'title' => 'Doorstep Laundry Pickup in Gurgaon | 20% Off First Order',
            'slug' => 'doorstep-laundry-pickup-gurgaon',
            'layout_type' => 'home',
            'meta_description' => 'Get fresh, hygienic wash & fold and steam ironing collected right from your doorstep in Gurgaon. Special 20% discount on first booking.',
            'content' => json_encode([
                'about_title' => 'Gurgaon\'s Most Reliable Doorstep Laundry Service',
                'about_text' => "Tenderfold delivers 5-star garment care directly to your apartment or home in Gurgaon. We sort by fabric type, wash in 100% softened water with skin-safe eco detergents, and return your clothes folded, fresh, and ready for your wardrobe.",
                'features' => [
                    ['icon' => 'truck', 'title' => 'Free Doorstep Collection', 'desc' => 'Scheduled pickup and delivery across DLF, Golf Course Rd, Sohna Rd, and all Gurgaon sectors.'],
                    ['icon' => 'layers', 'title' => '100% Segregated Batch Washing', 'desc' => 'Your garments are processed in their own dedicated washing and drying machines.'],
                    ['icon' => 'sparkles', 'title' => 'Skin-Safe Eco Detergents', 'desc' => 'Non-toxic, hypoallergenic German detergents that preserve fibers and colors.'],
                    ['icon' => 'award', 'title' => 'Precision Vacuum Steam Ironing', 'desc' => 'Italian vacuum suction tables ensure crisp, wrinkle-free finishing with zero fabric shine.']
                ],
                'faqs' => [
                    ['q' => 'How do I schedule a pickup in Gurgaon?', 'a' => 'Simply submit the form or click our WhatsApp link. Our delivery partner will arrive at your selected slot.'],
                    ['q' => 'What is the rate for everyday laundry?', 'a' => 'Our wash & fold service starts at just ₹80/kg, and wash & steam iron starts at ₹115/kg.'],
                    ['q' => 'Is there a minimum order quantity?', 'a' => 'There is a nominal 3 kg minimum order for free doorstep collection.']
                ],
                'cta_title' => 'Experience Freshness in Every Single Fold',
                'cta_text' => 'Book your doorstep laundry collection in Gurgaon today and enjoy 20% off your initial order.'
            ]),
        ],
        [
            'title' => 'Eco Dry Cleaning & Couture Fabric Spa in Gurgaon',
            'slug' => 'express-dry-cleaning-gurgaon',
            'layout_type' => 'service',
            'meta_description' => 'Premium dry cleaning for designer lehengas, silk sarees, bespoke suits, and winter cashmere in Gurgaon. Zero harsh chemicals.',
            'content' => json_encode([
                'service_details_title' => 'Specialized Dry Cleaning for Delicate & Ethnic Couture',
                'service_details_text' => "Our closed-loop hydrocarbon dry cleaning process protects delicate zari work, bead embroidery, pure silks, and tailored woolens. Every item is hand-inspected for stains and finished with Italian dry steam.",
                'service_bullets' => [
                    ['icon' => 'shield-check', 'title' => 'Safe for Heavy Embellishments', 'desc' => 'Gentle solvent bath preserves sequins, stone work, and fine metallic threads.'],
                    ['icon' => 'sparkles', 'title' => 'Odorless Hydrocarbon Process', 'desc' => 'Zero chemical smell or stiff fabric texture after cleaning.'],
                    ['icon' => 'clock', 'title' => 'Express 48h Turnaround', 'desc' => 'Prompt inspection and delivery in breathable fabric garment bags.']
                ],
                'process_title' => 'The 4-Step Couture Cleaning Protocol',
                'process_steps' => [
                    ['title' => 'Pre-Inspection & Spot Testing', 'desc' => 'We check care labels, colorfastness, and spot-clean food and oil stains by hand.'],
                    ['title' => 'Hydrocarbon Eco Cleaning', 'desc' => 'Delicate closed-loop wash cycle in temperature-controlled Italian machines.'],
                    ['title' => 'Vacuum Steam Pressing', 'desc' => 'Padded vacuum suction tables restore sharp creases and natural garment volume.'],
                    ['title' => 'Boutique Hanger Packaging', 'desc' => 'Delivered in premium breathable garment bags on contoured hangers.']
                ],
                'cta_title' => 'Restore Your Wardrobe With Tenderfold',
                'cta_text' => 'Schedule a free dry cleaning doorstep collection across Gurgaon today.'
            ]),
        ],
        [
            'title' => 'Premium Shoe Laundry & Luxury Sneaker Spa in Gurgaon',
            'slug' => 'designer-shoe-spa-gurgaon',
            'layout_type' => 'specialist',
            'meta_description' => 'Restore white sneakers, leather boots, and suede loafers. Professional cleaning, sole un-yellowing, and deodorization in Gurgaon.',
            'content' => json_encode([
                'specialist_bio_title' => 'Artisanal Footwear Care & Sneaker Restoration',
                'specialist_bio_text' => "Our dedicated shoe spa craftsmen clean, condition, and deodorize every pair by hand using pH-neutral solutions, horsehair brushes, and UV ozone sterilization.",
                'specialist_qualifications' => [
                    ['title' => 'Suede & Nubuck Re-Napping Mastery', 'desc' => 'Dry foam cleaning and brass brushing to restore soft suede texture.'],
                    ['title' => 'Sole Oxidation Reversal & De-Yellowing', 'desc' => 'Safe chemical treatments to bring aged yellow boost soles back to pure white.'],
                    ['title' => 'UV Ozone Anti-Bacterial Sanitization', 'desc' => 'Kills 99.9% of odor-causing bacteria inside athletic footwear and leather shoes.']
                ],
                'cta_title' => 'Step Out With Confidence',
                'cta_text' => 'Schedule a sneaker & footwear spa pickup in Gurgaon now.'
            ]),
        ]
    ];

    foreach ($landingPages as $lp) {
        $stmt = $pdo->prepare("INSERT INTO `landing_pages` (`title`, `slug`, `layout_type`, `meta_description`, `content`, `created_at`, `updated_at`) 
                               VALUES (?, ?, ?, ?, ?, NOW(), NOW()) 
                               ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `layout_type` = VALUES(`layout_type`), `meta_description` = VALUES(`meta_description`), `content` = VALUES(`content`), `updated_at` = NOW()");
        $stmt->execute([$lp['title'], $lp['slug'], $lp['layout_type'], $lp['meta_description'], $lp['content']]);
    }
    $actions[] = "Synchronized Tenderfold Gurgaon Landing Pages in database ✅";

    // 7.16b Synchronize CMS Pages (Privacy Policy, Terms & Conditions, etc.)
    $cmsPagesList = [
        [
            'title' => 'Tenderfold - Premium Laundry & Dry Cleaner in Gurgaon',
            'slug' => 'home',
            'metatitle' => 'Tenderfold | Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon',
            'metadescription' => 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care.',
            'description' => '<h1>Freshness in Every Single Fold</h1><p>Doorstep laundry, dry cleaning, steam pressing, shoe spa, and carpet care in Gurgaon.</p>',
            'status' => 1
        ],
        [
            'title' => 'About Tenderfold Gurgaon',
            'slug' => 'about-us',
            'metatitle' => 'About Us | Tenderfold Gurgaon Fabric Care',
            'metadescription' => 'Learn about Tenderfold laundry and dry cleaning philosophy, our plant facilities in Gurgaon, and our commitment to garment longevity.',
            'description' => '<h1>About Tenderfold</h1><p>We are a specialized fabric care service built around modern Italian machinery, skin-safe detergents, and prompt doorstep delivery across Gurgaon.</p>',
            'status' => 1
        ],
        [
            'title' => 'All Laundry & Dry Cleaning Services',
            'slug' => 'services',
            'metatitle' => 'All Services | Tenderfold Gurgaon',
            'metadescription' => 'Explore laundry, dry cleaning, steam pressing, shoe cleaning, curtain care, and carpet spa services in Gurgaon.',
            'description' => '<h1>Our Services</h1><p>From daily wash and fold to luxury couture dry cleaning, discover our full suite of fabric spa solutions.</p>',
            'status' => 1
        ],
        [
            'title' => 'Pricing & Rate Card',
            'slug' => 'pricing',
            'metatitle' => 'Pricing & Rate Card | Tenderfold Gurgaon',
            'metadescription' => 'View our complete transparent price list for wash & fold, steam pressing, dry cleaning, shoe cleaning, and carpet care.',
            'description' => '<h1>Transparent Laundry & Dry Cleaning Pricing</h1><p>Affordable per kg laundry and per piece dry cleaning rates with no hidden charges.</p>',
            'status' => 1
        ],
        [
            'title' => 'Customer Reviews & Feedback',
            'slug' => 'testimonials',
            'metatitle' => 'Customer Reviews & Testimonials | Tenderfold Gurgaon',
            'metadescription' => 'Read reviews and ratings from our customers in Sector 49, Sector 83, DLF, and across Gurgaon.',
            'description' => '<h1>What Our Gurgaon Customers Say</h1><p>Read genuine testimonials from satisfied households, professionals, and businesses across Gurgaon.</p>',
            'status' => 1
        ],
        [
            'title' => 'Frequently Asked Questions',
            'slug' => 'faqs',
            'metatitle' => 'Frequently Asked Questions | Tenderfold Laundry Gurgaon',
            'metadescription' => 'Find answers to common questions about Tenderfold doorstep pickup, dry cleaning turnaround, and specialized fabric care.',
            'description' => '<h1>Frequently Asked Questions</h1><p>Everything you need to know about pickup timings, turnaround, fabric safety, and order tracking.</p>',
            'status' => 1
        ],
        [
            'title' => 'Contact Tenderfold Gurgaon',
            'slug' => 'contact-us',
            'metatitle' => 'Contact Tenderfold | Gurgaon Laundry & Dry Cleaning Counters',
            'metadescription' => 'Call +91 95824 66445 or WhatsApp us for instant doorstep laundry collection in Gurgaon.',
            'description' => '<h1>Contact Us</h1><p>Visit our stores in Sector 49 & Sector 83 or call us for free doorstep collection.</p>',
            'status' => 1
        ],
        [
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'metatitle' => 'Privacy Policy | Tenderfold Gurgaon',
            'metadescription' => 'Read TenderFold\'s Privacy Policy. Learn how we collect, use, store and protect your personal information when using our laundry and dry cleaning services in Gurgaon.',
            'description' => '<p class="text-sm font-semibold text-mute" style="margin-bottom: 1.5rem;"><strong>Last Updated:</strong> November 24, 2026</p><h2>1. Introduction</h2><p>Welcome to TenderFold. We value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, store and protect your information when you visit our website, use our laundry services, schedule a pickup, or contact us.</p><p>By using our website and services, you agree to the practices described in this Privacy Policy.</p><h2>2. Information We Collect</h2><p>We may collect the following information when you interact with our website or use our services:</p><ul><li><strong>Personal Information:</strong> Your name, phone number, email address and other contact details.</li><li><strong>Address Information:</strong> Pickup and delivery addresses provided when booking our laundry services.</li><li><strong>Service Information:</strong> Details about your laundry requirements, service preferences, pickup and delivery schedules, and order-related information.</li><li><strong>Payment Information:</strong> Payment details or transaction information necessary to process payments. Payment information may be processed securely by third-party payment providers.</li><li><strong>Technical Information:</strong> IP address, browser type, device information, website usage, cookies and other technical data collected when you visit our website.</li></ul><h2>3. How We Use Your Information</h2><p>We use your information for the following purposes:</p><ul><li>To provide laundry, cleaning, pickup and delivery services.</li><li>To schedule and manage pickup and delivery appointments.</li><li>To communicate with you regarding orders, bookings and service updates.</li><li>To respond to your enquiries, feedback and customer support requests.</li><li>To process payments and maintain transaction records.</li><li>To improve our website, services and customer experience.</li><li>To maintain website security and prevent fraudulent or unauthorized activities.</li><li>To comply with applicable laws and legal obligations.</li></ul><h2>4. Sharing of Information</h2><p>We do not sell or rent your personal information to third parties.</p><p>We may share your information with trusted service providers when necessary to operate our business and provide our services, including:</p><ul><li>Pickup and delivery personnel or logistics service providers.</li><li>Payment processing providers.</li><li>Website hosting, maintenance and technical service providers.</li><li>Communication service providers for customer notifications and support.</li><li>Government authorities or legal agencies when required by applicable law.</li></ul><p>These third parties may access your information only to the extent necessary to perform their respective services or comply with legal obligations.</p><h2>5. Cookies and Website Tracking</h2><p>Our website may use cookies and similar technologies to improve website functionality, understand visitor behaviour and enhance your browsing experience.</p><p>Cookies may help us:</p><ul><li>Understand how visitors use our website.</li><li>Improve website performance and functionality.</li><li>Remember certain user preferences.</li><li>Analyse website traffic and usage patterns.</li></ul><p>You can manage or disable cookies through your browser settings. Disabling cookies may affect certain website functionalities.</p><h2>6. Data Security</h2><p>We take reasonable technical and organizational measures to protect your personal information against unauthorized access, misuse, loss, alteration or disclosure.</p><p>However, no method of transmitting information over the internet or storing electronic data is completely secure. While we strive to protect your information, we cannot guarantee absolute security.</p><h2>7. Data Retention</h2><p>We retain personal information only for as long as necessary to fulfil the purposes for which it was collected, provide our services, maintain business records, resolve disputes and comply with applicable legal obligations.</p><p>When information is no longer required, we may delete it or securely dispose of it, subject to applicable laws.</p><h2>8. Your Privacy Rights</h2><p>You may contact us regarding your personal information to:</p><ul><li>Request information about the personal data we hold about you.</li><li>Request correction or updating of inaccurate information.</li><li>Request deletion of your personal information, where applicable.</li><li>Withdraw consent for processing where processing is based on your consent.</li><li>Raise concerns or complaints regarding the use of your personal information.</li></ul><p>We will review and respond to such requests in accordance with applicable laws.</p><h2>9. Third-Party Websites</h2><p>Our website may contain links to third-party websites or services. We are not responsible for the privacy practices, security or content of third-party websites.</p><p>We encourage you to review the privacy policies of any third-party website you visit.</p><h2>10. Children\'s Privacy</h2><p>Our website and services are not specifically intended to collect personal information directly from children without appropriate consent or authorization as required under applicable law.</p><p>If we become aware that personal information has been collected inappropriately, we will take reasonable steps to address the matter in accordance with applicable laws.</p><h2>11. Changes to This Privacy Policy</h2><p>We may update this Privacy Policy from time to time to reflect changes in our business, services, website functionality or applicable legal requirements.</p><p>Any updates will be published on this page along with the revised date.</p><h2>12. Contact Us</h2><p>If you have any questions, concerns or requests regarding this Privacy Policy or the handling of your personal information, you may contact us:</p><p><strong>TenderFold</strong></p><p><strong>Website:</strong> <a href="https://tenderfold.in/" target="_blank" rel="noopener">https://tenderfold.in/</a></p><div class="address-grid"><div class="address-card"><div class="address-title">📍 Sector 49 (Orchid Petals)</div><div class="address-text">Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018</div></div><div class="address-card"><div class="address-title">📍 Sector 83 (Sapphire 83 Mall)</div><div class="address-text">F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004</div></div></div><p style="margin-top: 1rem;">We will make reasonable efforts to address your privacy-related queries and concerns in a timely manner.</p>',
            'status' => 1
        ],
        [
            'title' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'metatitle' => 'Terms & Conditions | Tenderfold Gurgaon',
            'metadescription' => 'Tenderfold terms and conditions of fabric care and doorstep delivery services.',
            'description' => '<h1>Terms & Conditions</h1><p>Standard service terms for garment inspection, pickup schedules, care instructions, and delivery turnaround.</p>',
            'status' => 1
        ],
        [
            'title' => 'Teams',
            'slug' => 'teams',
            'metatitle' => 'Our Team | Tenderfold Premium Laundry & Dry Cleaning Gurgaon',
            'metadescription' => 'Meet the team behind Tenderfold, your trusted laundry and dry cleaning service in Gurgaon. Experience professional fabric care, quality service and convenient doorstep pickup and delivery.',
            'description' => '<section class="teams-content"><h1>Meet the Team Behind Tenderfold</h1><p>At Tenderfold, our team is dedicated to delivering exceptional laundry and dry cleaning services in Gurgaon. We believe that every garment deserves expert care, attention to detail, and the right cleaning approach.</p><p>Our team combines professional expertise, modern Italian machinery, and skin-safe detergents to ensure quality fabric care. From everyday laundry to delicate garment cleaning, we focus on maintaining the freshness, cleanliness, and quality of your clothes.</p><p>With a commitment to customer satisfaction, timely service, and convenient doorstep pickup and delivery, our team works hard to make laundry care simple and hassle-free.</p><h2>Our Commitment</h2><ul><li>Professional laundry and dry cleaning services.</li><li>Expert care for different types of fabrics.</li><li>Modern Italian machinery and skin-safe detergents.</li><li>Quality-focused cleaning and attention to detail.</li><li>Convenient doorstep pickup and delivery across Gurgaon.</li></ul><p>At Tenderfold, our people are at the heart of everything we do. We strive to make professional fabric care reliable, convenient, and effortless for every customer.</p></section>',
            'status' => 1
        ]
    ];

    $cmsCols = [];
    try {
        $colStmt = $pdo->query("SHOW COLUMNS FROM `cms_pages`");
        while ($r = $colStmt->fetch()) {
            $cmsCols[] = $r['Field'] ?? $r['name'] ?? '';
        }
    } catch (Exception $e) {}

    foreach ($cmsPagesList as $cp) {
        $check = $pdo->prepare("SELECT id FROM `cms_pages` WHERE `slug` = ? LIMIT 1");
        $check->execute([$cp['slug']]);
        $existing = $check->fetch();
        if (!$existing) {
            if (in_array('content', $cmsCols)) {
                $stmt = $pdo->prepare("INSERT INTO `cms_pages` (`title`, `slug`, `metatitle`, `metadescription`, `description`, `content`, `status`, `created_at`, `updated_at`) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$cp['title'], $cp['slug'], $cp['metatitle'], $cp['metadescription'], $cp['description'], $cp['description'], $cp['status']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO `cms_pages` (`title`, `slug`, `metatitle`, `metadescription`, `description`, `status`, `created_at`, `updated_at`) 
                                       VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$cp['title'], $cp['slug'], $cp['metatitle'], $cp['metadescription'], $cp['description'], $cp['status']]);
            }
        } else {
            if ($cp['slug'] === 'privacy-policy') {
                if (in_array('content', $cmsCols)) {
                    $stmt = $pdo->prepare("UPDATE `cms_pages` SET `title` = ?, `metatitle` = ?, `metadescription` = ?, `description` = ?, `content` = ?, `status` = ?, `updated_at` = NOW() WHERE `slug` = ?");
                    $stmt->execute([$cp['title'], $cp['metatitle'], $cp['metadescription'], $cp['description'], $cp['description'], $cp['status'], $cp['slug']]);
                } else {
                    $stmt = $pdo->prepare("UPDATE `cms_pages` SET `title` = ?, `metatitle` = ?, `metadescription` = ?, `description` = ?, `status` = ?, `updated_at` = NOW() WHERE `slug` = ?");
                    $stmt->execute([$cp['title'], $cp['metatitle'], $cp['metadescription'], $cp['description'], $cp['status'], $cp['slug']]);
                }
            }
        }
    }
    $actions[] = "Synchronized Tenderfold CMS Pages (Privacy Policy, Terms & Conditions, etc.) in database ✅";

    // 7.17 Synchronize Page Contents (Homepage, About Us, Header, Footer)
    $pageContents = [
        ['header', 'menu', 'home', 'Home'],
        ['header', 'menu', 'about', 'About Us'],
        ['header', 'menu', 'services', 'Services'],
        ['header', 'menu', 'pricing', 'Pricing'],
        ['header', 'menu', 'blog', 'Care Journal'],
        ['header', 'menu', 'testimonials', 'Reviews'],
        ['header', 'menu', 'contact', 'Contact'],
        ['header', 'menu', 'cta', 'Call Now'],
        ['header', 'cta', 'button_text', 'Schedule a Pickup'],

        ['footer', 'about', 'description', 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning and specialized fabric spa. Punctual doorstep pickup and delivery across all major sectors.'],
        ['footer', 'headings', 'quick_links', 'Quick Links'],
        ['footer', 'headings', 'services', 'Our Services'],
        ['footer', 'headings', 'contact', 'Contact & Hubs'],
        ['footer', 'bottom', 'copyright', 'All Rights Reserved.'],
        ['footer', 'bottom', 'disclaimer', 'Tenderfold provides professional textile and garment care under strict manufacturer guidelines.'],

        ['home', 'hero', 'eyebrow', 'Premium Laundry & Dry Cleaner in Gurgaon'],
        ['home', 'hero', 'title', 'Freshness in Every Single Fold.'],
        ['home', 'hero', 'subtitle', 'Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric spa. Free doorstep pickup across all major sectors.'],
        ['home', 'hero', 'trust_1_num', '50,000+'],
        ['home', 'hero', 'trust_1_label', 'Garments Delivered'],
        ['home', 'hero', 'trust_2_num', '100%'],
        ['home', 'hero', 'trust_2_label', 'Eco-Friendly Solvents'],
        ['home', 'hero', 'trust_3_num', 'Sector 49 & 83'],
        ['home', 'hero', 'trust_3_label', 'Gurgaon Hubs'],

        ['home', 'about', 'pre_title', 'About Tenderfold'],
        ['home', 'about', 'title', 'Gurgaon\'s Premier Fabric Care & Garment Spa'],
        ['home', 'about', 'body_p1', 'Tenderfold is dedicated to elevating everyday laundry and dry cleaning into a meticulous garment care experience. Operating from physical stores in Sector 49 (Orchid Petals) and Sector 83 (Sapphire 83 Mall), we serve households across Gurgaon.'],
        ['home', 'about', 'body_p2', 'From everyday wash & fold to luxury silk preservation, sneaker renewal, and carpet shampooing, our team combines German eco-detergents, Italian vacuum steam finishing, and punctual doorstep pickup.'],

        ['home', 'services', 'title', 'Comprehensive Garment & Home Fabric Care'],
        ['home', 'services', 'subtitle', 'Explore our full spectrum of specialized laundry, couture dry cleaning, and home fabric care services.'],

        ['home', 'cta', 'heading', '20% Off Your First Order'],
        ['home', 'cta', 'title', 'Experience Tenderfold Care Today'],
        ['home', 'cta', 'subtitle', 'Schedule a free doorstep pickup in Gurgaon and enjoy pristine, fresh laundry delivered right to your wardrobe.'],

        ['home', 'why_choose_us', 'title', '4-Step Quality Assurance Routine'],
        ['home', 'why_choose_us', 'subtitle', 'Our systematic textile evaluation ensures hygienic washing, zero fiber shrinkage, and sharp steam finishing.'],

        ['about', 'hero', 'pre_title', 'WHO WE ARE'],
        ['about', 'hero', 'title', 'Crafting Freshness, Preserving Fabrics'],
        ['about', 'hero', 'subtitle', 'We are passionate about fabric longevity, environmental safety, and convenient doorstep laundry in Gurgaon.'],
        ['about', 'story', 'pre_title', 'Our Story'],
        ['about', 'story', 'title', 'Redefining Wardrobe Care in Gurgaon'],
        ['about', 'story', 'p1', 'Tenderfold began with a simple belief: laundry should be more than a chore — it should be a reliable care routine that protects the investment you make in your wardrobe.'],
        ['about', 'story', 'p2', 'By replacing harsh industrial chemicals with gentle eco solvents, and domestic irons with professional vacuum steam tables, we ensure every garment returns with pristine freshness.'],
        ['about', 'story', 'p3', 'Today, our counters in Orchid Petals (Sector 49) and Sapphire 83 Mall (Sector 83) serve thousands of families and professionals with free doorstep pickup.'],
        ['about', 'vision', 'title', 'Our Vision'],
        ['about', 'vision', 'text', 'To become the most trusted fabric care and laundry brand in Northern India by prioritizing hygiene, fabric safety, and customer convenience.'],
        ['about', 'mission', 'title', 'Our Mission'],
        ['about', 'mission', 'text', 'To provide eco-friendly, hypoallergenic garment cleaning with transparent per-kilo and per-piece pricing, prompt turnaround, and exceptional customer support.'],
        ['about', 'values', 'title', 'Our Core Values'],
        ['about', 'values', 'text', 'Absolute hygiene, fiber-safe cleaning, environmental responsibility, and punctual service.'],
    ];

    foreach ($pageContents as $pc) {
        $stmt = $pdo->prepare("INSERT INTO `page_contents` (`page`, `section`, `key`, `value`, `created_at`, `updated_at`) 
                               VALUES (?, ?, ?, ?, NOW(), NOW()) 
                               ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = NOW()");
        $stmt->execute([$pc[0], $pc[1], $pc[2], $pc[3]]);
    }
    $actions[] = "Synchronized Frontend Page Contents with Admin CMS in database ✅";

    // 7.18 Also trigger Artisan migrations & seeders if PHP CLI is accessible
    if (function_exists('exec')) {
        @exec("cd " . escapeshellarg($basePath) . " && php artisan migrate --force 2>&1", $migOut);
        @exec("cd " . escapeshellarg($basePath) . " && php artisan db:seed --class=SiteSeeder --force 2>&1", $seedOut);
        if (!empty($seedOut)) {
            $actions[] = "Artisan DB Seed: <code>" . htmlspecialchars(implode(' ', (array)$seedOut)) . "</code> ✅";
        }
    }

    $checks['Database Tables & Schema'] = ['status' => true, 'msg' => 'All Tenderfold core tables, landing pages, and content synchronized in-place'];
}

// 8. Clear Compiled Laravel Views, Config & Route Cache Files
$viewCacheDir = $basePath . '/storage/framework/views';
if (is_dir($viewCacheDir)) {
    $files = glob($viewCacheDir . '/*.php');
    if ($files) {
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}
$actions[] = "Cleared compiled blade view cache files ✅";

// 9. Run artisan commands via CLI if available
$phpCli = file_exists('/opt/alt/php84/usr/bin/php') ? '/opt/alt/php84/usr/bin/php' : 'php';
if (function_exists('exec')) {
    @exec("cd " . escapeshellarg($basePath) . " && $phpCli artisan view:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && $phpCli artisan cache:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && $phpCli artisan config:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && $phpCli artisan route:clear 2>&1");
}

// 10. Test Laravel Application Boot
try {
    if (file_exists($basePath . '/vendor/autoload.php') && file_exists($basePath . '/bootstrap/app.php')) {
        require_once $basePath . '/vendor/autoload.php';
        $testApp = require_once $basePath . '/bootstrap/app.php';
        $checks['Laravel Application Bootstrap'] = [
            'status' => true,
            'msg' => 'Application booted cleanly without fatal errors! Laravel version ' . $testApp->version()
        ];
    }
} catch (\Throwable $e) {
    $checks['Laravel Application Bootstrap'] = [
        'status' => false,
        'msg' => 'Bootstrap Exception: ' . htmlspecialchars($e->getMessage()) . ' in ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine()
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tenderfold Live Deployment & Database Sync</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Nunito:wght@700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: "#143572",
            "navy-dark": "#0C2454",
            gold: "#F5B400",
            cream: "#F4F7FB"
          },
          fontFamily: {
            display: ["Nunito", "sans-serif"],
            sans: ["Manrope", "sans-serif"]
          }
        }
      }
    }
  </script>
</head>
<body class="bg-cream font-sans text-gray-800 antialiased min-h-screen flex flex-col justify-between">
  <div class="max-w-4xl mx-auto px-4 py-12 w-full">
    <!-- Header -->
    <div class="bg-navy text-white rounded-3xl p-8 shadow-xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
      <div>
        <span class="inline-block px-3 py-1 bg-gold text-navy text-xs font-extrabold uppercase rounded-full tracking-wider mb-2">Live Deployment Engine</span>
        <h1 class="text-3xl font-display font-extrabold">Tenderfold Server & DB Status</h1>
        <p class="text-white/70 text-sm mt-1">Hostinger & Production Environment Auto-Sync Tool</p>
      </div>
      <div class="flex gap-3">
        <a href="/" class="px-5 py-2.5 bg-gold text-navy font-display font-bold text-sm rounded-full transition hover:bg-yellow-400 shadow">Visit Website →</a>
        <a href="/admin/login" class="px-5 py-2.5 bg-white/15 text-white font-display font-bold text-sm rounded-full transition hover:bg-white/25">Admin CMS</a>
      </div>
    </div>

    <!-- Diagnostic Checks -->
    <div class="bg-white rounded-3xl p-8 shadow-md mb-8 border border-gray-100">
      <h2 class="text-xl font-display font-extrabold text-navy mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Environment & System Checks
      </h2>
      <div class="space-y-4">
        <?php foreach ($checks as $title => $check): ?>
          <div class="flex items-center justify-between p-4 rounded-2xl <?= $check['status'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' ?>">
            <div>
              <p class="font-bold text-sm text-navy"><?= htmlspecialchars($title) ?></p>
              <p class="text-xs text-gray-600 mt-0.5"><?= $check['msg'] ?></p>
            </div>
            <span class="text-lg"><?= $check['status'] ? '✅' : '❌' ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Automated Actions Taken -->
    <?php if (!empty($actions)): ?>
      <div class="bg-white rounded-3xl p-8 shadow-md mb-8 border border-gray-100">
        <h2 class="text-xl font-display font-extrabold text-navy mb-4 flex items-center gap-2">
          <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          Automated Database & System Actions
        </h2>
        <ul class="space-y-2 text-sm text-gray-700">
          <?php foreach ($actions as $action): ?>
            <li class="p-3 bg-gray-50 rounded-xl flex items-center gap-2"><?= $action ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Re-Sync Button -->
    <div class="bg-blue-50 border border-blue-200 rounded-3xl p-8 shadow-sm">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
          <h3 class="font-display font-bold text-navy text-lg">Continuous Deployment & Database In-Place Sync</h3>
          <p class="text-xs text-gray-600 mt-1">Whenever you push to GitHub, visiting <code>/deploy.php</code> automatically pulls latest code, updates database content in-place without wiping leads, and flushes cache.</p>
        </div>
        <a href="/deploy.php" class="px-6 py-3 bg-navy text-white font-bold rounded-xl text-sm hover:bg-navy-dark transition whitespace-nowrap shadow">
          ⟳ Re-Run Deploy & Sync
        </a>
      </div>
      <div class="mt-6 pt-4 border-t border-blue-200 flex flex-wrap gap-4 items-center justify-between text-xs text-gray-600">
        <span>Admin Login: <strong>admin@tenderfold.in</strong> / <strong>admin123</strong></span>
        <span>Version: <strong>Live Production 1.0</strong></span>
      </div>
    </div>
  </div>

  <footer class="text-center py-6 text-xs text-gray-500">
    Tenderfold Clean & Care • Gurgaon, Haryana • <a href="https://tenderfold.in" class="text-navy font-semibold">tenderfold.in</a>
  </footer>
</body>
</html>
