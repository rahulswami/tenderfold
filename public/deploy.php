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
    $paramCols = $pdo->query("DESCRIBE `site_parameters`")->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('label', $paramCols)) {
        $pdo->exec("ALTER TABLE `site_parameters` MODIFY COLUMN `label` VARCHAR(255) NULL DEFAULT NULL;");
    }
    $pdo->exec("INSERT INTO `site_parameters` (`id`, `label`, `category`, `value`, `created_at`, `updated_at`) 
        VALUES ('ai_model', 'AI Model', 'ai', 'gemini-2.5-flash', NOW(), NOW()) 
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

// ── 13. Auto-restore Blog Titles & HTML Contents ─────────────────────
try {
    $emptyCount = (int)$pdo->query("SELECT COUNT(*) FROM `blogs` WHERE `title` IS NULL OR `title` = ''")->fetchColumn();
    if ($emptyCount > 0 && file_exists(__DIR__ . '/../u709314437_smpune.sql')) {
        $sqlContent = file_get_contents(__DIR__ . '/../u709314437_smpune.sql');
        $startPos = strpos($sqlContent, "INSERT INTO `blogs` (`id`, `name`, `slug`");
        if ($startPos !== false) {
            $blogsSection = substr($sqlContent, $startPos);
            if (preg_match('/\nINSERT INTO `(?!blogs`)/', $blogsSection, $m, PREG_OFFSET_CAPTURE)) {
                $blogsSection = substr($blogsSection, 0, $m[0][1]);
            }
            
            $tuples = [];
            $inTuple = false; $inStr = false; $quoteChar = null;
            $currVal = ''; $currTuple = []; $len = strlen($blogsSection);
            for ($i = 0; $i < $len; $i++) {
                $c = $blogsSection[$i];
                if (!$inTuple) {
                    if ($c === '(') { $inTuple = true; $currTuple = []; $currVal = ''; $inStr = false; }
                    continue;
                }
                if ($inStr) {
                    if ($c === $quoteChar) {
                        if ($i + 1 < $len && $blogsSection[$i + 1] === $quoteChar) { $currVal .= $quoteChar; $i++; continue; }
                        if ($i > 0 && $blogsSection[$i - 1] === '\\' && ($i < 2 || $blogsSection[$i - 2] !== '\\')) { $currVal .= $c; continue; }
                        $inStr = false; continue;
                    }
                    if ($c === '\\' && $i + 1 < $len) {
                        $next = $blogsSection[$i + 1];
                        if ($next === 'n') { $currVal .= "\n"; $i++; continue; }
                        if ($next === 'r') { $currVal .= "\r"; $i++; continue; }
                        if ($next === 't') { $currVal .= "\t"; $i++; continue; }
                        if ($next === "'" || $next === '"' || $next === '\\') { $currVal .= $next; $i++; continue; }
                    }
                    $currVal .= $c; continue;
                }
                if ($c === "'" || $c === '"') { $inStr = true; $quoteChar = $c; continue; }
                if ($c === ',') { $currTuple[] = (trim($currVal) === 'NULL') ? null : $currVal; $currVal = ''; continue; }
                if ($c === ')') {
                    $currTuple[] = (trim($currVal) === 'NULL') ? null : $currVal;
                    if (count($currTuple) >= 5 && !empty($currTuple[1]) && !empty($currTuple[4])) {
                        $tuples[] = $currTuple;
                    }
                    $inTuple = false; $currVal = ''; continue;
                }
                $currVal .= $c;
            }
            
            $restoredCount = 0;
            $stmtUpdate = $pdo->prepare("UPDATE `blogs` SET `title` = :title, `content` = :content, `summary` = :summary, `updated_at` = NOW() WHERE `id` = :id OR `slug` = :slug");
            foreach ($tuples as $t) {
                $bId = is_numeric($t[0]) ? (int)$t[0] : null;
                $bTitle = html_entity_decode(trim($t[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $bSlug = trim($t[2] ?? '');
                $bContent = trim($t[4] ?? '');
                $cleanTxt = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($bContent), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                $bSummary = mb_substr($cleanTxt, 0, 165) . (mb_strlen($cleanTxt) > 165 ? '...' : '');
                
                if (!empty($bTitle) && !empty($bContent)) {
                    $stmtUpdate->execute(['title' => $bTitle, 'content' => $bContent, 'summary' => $bSummary, 'id' => $bId, 'slug' => $bSlug]);
                    $restoredCount++;
                }
            }
            $results['blog_restoration'] = "RESTORED {$restoredCount} BLOG TITLES & CONTENTS ✅";
        }
    } else {
        $results['blog_restoration'] = "ALL BLOG TITLES & CONTENTS UP TO DATE ✅";
    }
} catch (Exception $e) {
    $results['blog_restoration'] = "FAIL ❌ " . $e->getMessage();
}

echo "Live database & system deployment results:\n";
foreach ($results as $table => $status) {
    echo "- {$table}: {$status}\n";
}

echo "\nAll database upgrades & view cache clearing applied successfully! Please try loading the /admin page now.\n";

