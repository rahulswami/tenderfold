<?php
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;

header('Content-Type: text/plain; charset=utf-8');

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);

echo "=== TENDERFOLD SQLITE SETUP & SEED ===\n\n";

$sqlitePath = $basePath . '/database/database.sqlite';
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}
@chmod($sqlitePath, 0666);

// 2. Set .env to SQLite
$envPath = $basePath . '/.env';
$envContent = <<<ENV
APP_NAME=TenderFold
APP_ENV=production
APP_KEY=base64:oJm2o5R6a8G+z1Xb2U4p3K4q5R6s7T8u9V0w1X2y3Z4=
APP_DEBUG=false
APP_URL=https://tenderfold.in

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE={$sqlitePath}

BROADCAST_DRIVER=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
ENV;

file_put_contents($envPath, $envContent);
file_put_contents($basePath . '/.env.backup', $envContent);
echo "Updated .env and .env.backup to SQLite\n\n";

// Clear bootstrap cache
$bootstrapCacheFiles = glob($basePath . '/bootstrap/cache/*.php');
foreach ($bootstrapCacheFiles as $bf) {
    if (basename($bf) !== 'packages.php' && basename($bf) !== 'services.php') @unlink($bf);
}

// 3. Run artisan migrate and db:seed
echo "=== Running Migrations & Seeder ===\n";
$migOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && php artisan migrate --force 2>&1", $migOutput);
echo "Migrate Output:\n" . implode("\n", $migOutput) . "\n\n";

$seedOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && php artisan db:seed --class=SiteSeeder --force 2>&1", $seedOutput);
echo "Seed Output:\n" . implode("\n", $seedOutput) . "\n\n";

// 4. Inspect SQLite database
echo "=== Inspecting SQLite Database ===\n";
echo "File size: " . filesize($sqlitePath) . " bytes\n";
try {
    $pdo = new PDO("sqlite:" . $sqlitePath);
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in SQLite (" . count($tables) . "): " . implode(', ', $tables) . "\n\n";
    
    if (in_array('services', $tables)) {
        $services = $pdo->query("SELECT service_name, slug, rate_value FROM services")->fetchAll(PDO::FETCH_ASSOC);
        echo "Services in database (" . count($services) . "):\n";
        foreach ($services as $s) {
            echo "  - {$s['service_name']} ({$s['slug']}): {$s['rate_value']}\n";
        }
        echo "\n";
    }
    
    if (in_array('blogs', $tables)) {
        $blogs = $pdo->query("SELECT title, slug FROM blogs")->fetchAll(PDO::FETCH_ASSOC);
        echo "Blogs in database (" . count($blogs) . "):\n";
        foreach ($blogs as $b) {
            echo "  - {$b['title']} ({$b['slug']})\n";
        }
        echo "\n";
    }
    
    if (in_array('users', $tables)) {
        $users = $pdo->query("SELECT name, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
        echo "Admin Users (" . count($users) . "):\n";
        foreach ($users as $u) {
            echo "  - {$u['name']} <{$u['email']}> [{$u['role']}]\n";
        }
        echo "\n";
    }
} catch (\Throwable $e) {
    echo "SQLite Query Error: " . $e->getMessage() . "\n";
}

// 5. Clear all Laravel view and application cache
$viewFiles = glob($basePath . '/storage/framework/views/*.php');
foreach ($viewFiles as $vf) @unlink($vf);

@exec("cd " . escapeshellarg($basePath) . " && php artisan view:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && php artisan cache:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && php artisan config:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && php artisan route:clear 2>&1");

if (function_exists('opcache_reset')) @opcache_reset();

echo "=== Done ===\n";
