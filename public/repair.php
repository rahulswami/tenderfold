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

echo "=== TENDERFOLD MIGRATIONS & SEEDS VIA PHP 8.4 ===\n\n";

$php84 = '/opt/alt/php84/usr/bin/php';
if (!file_exists($php84)) {
    die("PHP 8.4 binary not found at $php84\n");
}

$sqlitePath = $basePath . '/database/database.sqlite';
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}
@chmod($sqlitePath, 0666);

// 2. Run migrate
$mig = [];
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan migrate --force 2>&1", $mig);
echo "Migrate Output:\n" . implode("\n", $mig) . "\n\n";

// 3. Run SiteSeeder
$seed = [];
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan db:seed --class=SiteSeeder --force 2>&1", $seed);
echo "Seed Output:\n" . implode("\n", $seed) . "\n\n";

// 4. Verify SQLite contents
echo "SQLite DB size: " . filesize($sqlitePath) . " bytes\n";
try {
    $pdo = new PDO("sqlite:" . $sqlitePath);
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables created in SQLite (" . count($tables) . "): " . implode(', ', $tables) . "\n\n";
    
    if (in_array('services', $tables)) {
        $services = $pdo->query("SELECT service_name, slug, rate_value FROM services")->fetchAll(PDO::FETCH_ASSOC);
        echo "Services seeded (" . count($services) . "):\n";
        foreach ($services as $s) {
            echo "  - {$s['service_name']} ({$s['slug']}): {$s['rate_value']}\n";
        }
        echo "\n";
    }
} catch (\Throwable $e) {
    echo "Query Error: " . $e->getMessage() . "\n";
}

// 5. Clear caches
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan view:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan cache:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan config:clear 2>&1");
@exec("cd " . escapeshellarg($basePath) . " && $php84 artisan route:clear 2>&1");

$viewFiles = glob($basePath . '/storage/framework/views/*.php');
foreach ($viewFiles as $vf) @unlink($vf);

if (function_exists('opcache_reset')) @opcache_reset();

echo "=== Setup Complete ===\n";
