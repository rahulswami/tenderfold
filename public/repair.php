<?php
// Immediate Self-Healing & Environment Restoration Script
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;
$homeDir = '/home/u709314437';

header('Content-Type: text/plain; charset=utf-8');

echo "=== TENDERFOLD ENVIRONMENT REPAIR ===\n";

// 1. Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);
echo "Git Pull: " . implode("\n", $gitOutput) . "\n\n";

$headOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git rev-parse HEAD 2>&1", $headOutput);
echo "Current Git Commit: " . implode("", $headOutput) . "\n\n";

// 2. Restore .env
$envTarget = $basePath . '/.env';
$backupFile = $homeDir . '/.env.backup';

echo "Target .env: $envTarget\n";
echo "Backup File: $backupFile (exists: " . (file_exists($backupFile) ? 'YES, size=' . filesize($backupFile) . 'b' : 'NO') . ")\n\n";

if (file_exists($backupFile)) {
    $content = file_get_contents($backupFile);
    if ($content !== false && strlen($content) > 50) {
        $written = file_put_contents($envTarget, $content);
        @chmod($envTarget, 0644);
        echo "Successfully wrote $written bytes to $envTarget\n";
        
        // Also save a copy inside basePath
        @file_put_contents($basePath . '/.env.backup', $content);
        echo "Saved safety copy to $basePath/.env.backup\n\n";
    } else {
        echo "ERROR: Backup file was empty or could not be read\n\n";
    }
} else {
    echo "ERROR: Backup file $backupFile does not exist\n\n";
}

// 3. Inspect the newly written .env
if (file_exists($envTarget)) {
    echo "=== .env Inspection ===\n";
    $lines = file($envTarget, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $parsedEnv = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            $parsedEnv[$key] = $val;
            if (stripos($key, 'DB_') !== false || in_array($key, ['APP_NAME', 'APP_ENV', 'APP_URL', 'APP_DEBUG'])) {
                echo "$key = $val\n";
            }
        }
    }
    echo "\n";
    
    // 4. Test MySQL Database Connection directly
    echo "=== Testing Database Connection ===\n";
    $dbHost = $parsedEnv['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $parsedEnv['DB_PORT'] ?? '3306';
    $dbName = $parsedEnv['DB_DATABASE'] ?? '';
    $dbUser = $parsedEnv['DB_USERNAME'] ?? '';
    $dbPass = $parsedEnv['DB_PASSWORD'] ?? '';
    
    echo "Connecting to MySQL host=$dbHost;port=$dbPort;dbname=$dbName as user=$dbUser...\n";
    try {
        $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        echo "SUCCESS: Database connected!\n";
        
        // Count tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Total tables in database: " . count($tables) . "\n";
        echo "Tables: " . implode(', ', array_slice($tables, 0, 15)) . (count($tables) > 15 ? '...' : '') . "\n\n";
        
        // Check users
        if (in_array('users', $tables)) {
            $userCount = $pdo->query("SELECT count(*) FROM users")->fetchColumn();
            echo "Users in table: $userCount\n";
        }
    } catch (PDOException $e) {
        echo "DATABASE ERROR: " . $e->getMessage() . "\n\n";
    }
}

// 5. Clear all Laravel caches
echo "=== Clearing Laravel Caches ===\n";
$cacheDirs = [
    $basePath . '/bootstrap/cache',
    $basePath . '/storage/framework/views',
    $basePath . '/storage/framework/cache/data',
    $basePath . '/storage/framework/sessions'
];
foreach ($cacheDirs as $cd) {
    if (is_dir($cd)) {
        $files = glob($cd . '/*');
        foreach ($files as $f) {
            if (is_file($f) && basename($f) !== 'packages.php' && basename($f) !== 'services.php') {
                @unlink($f);
            }
        }
        echo "Cleared $cd\n";
    }
}

if (function_exists('exec')) {
    @exec("cd " . escapeshellarg($basePath) . " && php artisan view:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && php artisan cache:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && php artisan config:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && php artisan route:clear 2>&1");
    echo "Executed artisan clear commands\n\n";
}

// 6. Test Laravel Bootstrap
echo "=== Testing Laravel Bootstrap ===\n";
try {
    require_once $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    echo "SUCCESS: Laravel application booted! Version: " . $app->version() . "\n";
} catch (\Throwable $e) {
    echo "BOOTSTRAP ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
}

if (function_exists('opcache_reset')) {
    @opcache_reset();
    echo "Reset OPcache\n";
}

echo "\n=== Done ===\n";
