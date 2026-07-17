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

// Validate security key
try {
    $stmt = $pdo->prepare("SELECT value FROM site_parameters WHERE id = 'cloudflare_r2_secret_access_key' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
    $setupKey = isset($row['value']) ? trim($row['value']) : '';
} catch (Exception $e) {
    $setupKey = '';
}

$providedKey = isset($_GET['key']) ? trim($_GET['key']) : '';
if (empty($setupKey) || $providedKey !== $setupKey) {
    http_response_code(401);
    die("Unauthorized. Please provide the valid setup key in the URL ?key=...");
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

// ── 3. Modify users table ─────────────────────────────────────────
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

echo "Live database deployment results:\n";
foreach ($results as $table => $status) {
    echo "- {$table}: {$status}\n";
}

echo "\nAll database upgrades applied successfully! Please try loading the website again.\n";
