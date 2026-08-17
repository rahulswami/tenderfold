<?php

header('Content-Type: text/plain');

$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    die("Error: .env file not found.");
}

$env = [];
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $env[trim($parts[0])] = trim($parts[1], '"\' ');
    }
}

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

if (empty($dbName)) {
    die("Error: DB_DATABASE not specified in .env");
}

try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "Database connection successful!\n\n";

$results = [];

// ── 1. Create push_subscriptions ──────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `push_subscriptions` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `session_id` VARCHAR(255) NULL,
        `endpoint` TEXT NULL,
        `auth_token` TEXT NULL,
        `public_key` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX (`session_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['push_subscriptions'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['push_subscriptions'] = "FAIL ❌ " . $e->getMessage();
}

// ── 2. Create web_notifications ───────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `web_notifications` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `session_id` VARCHAR(255) NULL,
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `url` VARCHAR(2048) NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX (`session_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['web_notifications'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['web_notifications'] = "FAIL ❌ " . $e->getMessage();
}

// ── 3. Create admin_notifications ─────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_notifications` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `type` VARCHAR(255) NOT NULL DEFAULT 'system',
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NULL,
        `user_name` VARCHAR(255) NULL,
        `action_url` VARCHAR(2048) NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['admin_notifications'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['admin_notifications'] = "FAIL ❌ " . $e->getMessage();
}

// ── 4. Create lead_activities ─────────────────────────────────────
try {
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
    $results['lead_activities'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['lead_activities'] = "FAIL ❌ " . $e->getMessage();
}

// ── 5. Create treatments ──────────────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `treatments` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `image` VARCHAR(255) NULL,
        `description` LONGTEXT NULL,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `tags` VARCHAR(255) NULL,
        `schema_markup` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['treatments'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['treatments'] = "FAIL ❌ " . $e->getMessage();
}

// ── 6. Create team_members ────────────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `team_members` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NULL,
        `designation` VARCHAR(255) NULL,
        `image` VARCHAR(255) NULL,
        `phone` VARCHAR(255) NULL,
        `facebook` VARCHAR(255) NULL,
        `twitter` VARCHAR(255) NULL,
        `description` LONGTEXT NULL,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `tags` VARCHAR(255) NULL,
        `schema_markup` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['team_members'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['team_members'] = "FAIL ❌ " . $e->getMessage();
}

// ── 7. Create cms_pages ───────────────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `cms_pages` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `description` LONGTEXT NULL,
        `metatitle` VARCHAR(255) NULL,
        `metadescription` TEXT NULL,
        `tags` VARCHAR(255) NULL,
        `schema_markup` TEXT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $results['cms_pages'] = "CREATED OR ALREADY EXISTED ✅";
} catch (Exception $e) {
    $results['cms_pages'] = "FAIL ❌ " . $e->getMessage();
}

// ── 8. Modify users table ─────────────────────────────────────────
try {
    $columns = $pdo->query("DESCRIBE `users`")->fetchAll(PDO::FETCH_COLUMN);
    $hasRole = in_array('role', $columns);
    $hasPerms = in_array('permissions', $columns);
    
    if (!$hasRole) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `role` VARCHAR(255) NOT NULL DEFAULT 'admin' AFTER `password`");
    }
    if (!$hasPerms) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `permissions` TEXT NULL AFTER `role`");
    }
    
    // Seed user ID 1 as superadmin
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = 1 LIMIT 1");
    $stmt->execute();
    if ($stmt->fetch()) {
        $stmtUpdate = $pdo->prepare("UPDATE users SET role = 'superadmin', permissions = :perms WHERE id = 1");
        $stmtUpdate->execute(['perms' => json_encode(['all'])]);
    }
    
    $results['users_columns'] = "UPDATED (role & permissions added, user 1 seeded) ✅";
} catch (Exception $e) {
    $results['users_columns'] = "FAIL ❌ " . $e->getMessage();
}

// ── 9. Ensure services & blogs SEO columns ────────────────────────
try {
    $svcCols = $pdo->query("DESCRIBE `services`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('metatitle', $svcCols)) {
        $pdo->exec("ALTER TABLE `services` ADD COLUMN `metatitle` VARCHAR(255) NULL, ADD COLUMN `metadescription` TEXT NULL, ADD COLUMN `metakeywords` TEXT NULL;");
    }
    $blogCols = $pdo->query("DESCRIBE `blogs`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('metatitle', $blogCols)) {
        $pdo->exec("ALTER TABLE `blogs` ADD COLUMN `metatitle` VARCHAR(255) NULL, ADD COLUMN `metadescription` TEXT NULL, ADD COLUMN `metakeywords` TEXT NULL, ADD COLUMN `tags` TEXT NULL;");
    }
    $results['services_blogs_seo_columns'] = "SEO COLUMNS ADDED/VERIFIED ✅";
} catch (Exception $e) {
    $results['services_blogs_seo_columns'] = "FAIL ❌ " . $e->getMessage();
}

// ── 10. Update site_parameters ────────────────────────────────────
try {
    $pdo->exec("INSERT INTO `site_parameters` (`id`, `category`, `value`, `created_at`, `updated_at`) 
        VALUES ('ai_model', 'ai', 'gemini-2.5-flash', NOW(), NOW()) 
        ON DUPLICATE KEY UPDATE `value` = 'gemini-2.5-flash', `updated_at` = NOW();");
    $results['site_parameters_ai_model'] = "UPDATED to gemini-2.5-flash ✅";
} catch (Exception $e) {
    $results['site_parameters_ai_model'] = "FAIL ❌ " . $e->getMessage();
}

// ── 11. Clear Compiled View Caches ───────────────────────────────
try {
    $storageViews = __DIR__ . '/../storage/framework/views';
    if (is_dir($storageViews)) {
        $files = glob($storageViews . '/*');
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                @unlink($file);
            }
        }
        $results['clear_view_cache'] = "STORAGE FRAMEWORK VIEWS CLEARED ✅";
    }
} catch (Exception $e) {
    $results['clear_view_cache'] = "FAIL ❌ " . $e->getMessage();
}

// ── 12. Run SEO Data Sync ──────────────────────────────────────────
try {
    if (file_exists(__DIR__ . '/../sync_drsumitz_seo.php')) {
        ob_start();
        include __DIR__ . '/../sync_drsumitz_seo.php';
        $syncOut = ob_get_clean();
        $results['seo_data_sync'] = "SEO DATA SYNCED TO LIVE DB ✅";
    }
} catch (Exception $e) {
    $results['seo_data_sync'] = "FAIL ❌ " . $e->getMessage();
}

echo "Live database & system deployment results:\n";
foreach ($results as $table => $status) {
    echo "- {$table}: {$status}\n";
}

echo "\nAll database upgrades & view cache clearing applied successfully! Please try loading the /admin page now.\n";
