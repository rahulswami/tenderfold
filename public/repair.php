<?php
$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;

header('Content-Type: text/plain; charset=utf-8');

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);

echo "=== TENDERFOLD PARAMETERS SYNC ===\n\n";

// Connect to database from .env
$envPath = $basePath . '/.env';
$env = parse_ini_file($envPath);

$pdo = new PDO("mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};charset=utf8mb4", $env['DB_USERNAME'], $env['DB_PASSWORD'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Check site_parameters with Savasth
$stmt = $pdo->query("SELECT id, value FROM site_parameters WHERE value LIKE '%Savasth%'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Parameters containing 'Savasth' (" . count($rows) . "):\n";
foreach ($rows as $r) {
    echo "  {$r['id']} => {$r['value']}\n";
}

// Update site_name, site_tagline, meta_description, copyright_text, footer_description
$pdo->exec("UPDATE site_parameters SET value = 'Tenderfold' WHERE id = 'site_name'");
$pdo->exec("UPDATE site_parameters SET value = 'Freshness in Every Single Fold - Premium Laundry & Dry Cleaner in Gurgaon' WHERE id = 'site_tagline'");
$pdo->exec("UPDATE site_parameters SET value = 'Tenderfold is a premium laundry and dry cleaner in Gurgaon. Doorstep pickup for wash & fold, steam ironing, dry cleaning and specialized fabric care. Call +91 95824 66445.' WHERE id = 'meta_description'");
$pdo->exec("UPDATE site_parameters SET value = '© 2026 Tenderfold. Freshness in Every Single Fold. All Rights Reserved.' WHERE id = 'copyright_text'");
$pdo->exec("UPDATE site_parameters SET value = 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric care service. Doorstep pickup across all major sectors.' WHERE id = 'footer_description'");

// Update any other remaining Savasth occurrences
$pdo->exec("UPDATE site_parameters SET value = REPLACE(value, 'Savasth', 'Tenderfold') WHERE value LIKE '%Savasth%'");

// Check again
$stmt2 = $pdo->query("SELECT id, value FROM site_parameters WHERE id IN ('site_name', 'site_tagline', 'meta_description', 'copyright_text', 'footer_description')");
echo "\nUpdated core parameters:\n";
foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "  {$r['id']} => {$r['value']}\n";
}

// Clear Laravel view and config cache
$viewFiles = glob($basePath . '/storage/framework/views/*.php');
foreach ($viewFiles as $vf) @unlink($vf);

if (function_exists('exec')) {
    @exec("cd " . escapeshellarg($basePath) . " && php artisan view:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && php artisan cache:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && php artisan config:clear 2>&1");
}

if (function_exists('opcache_reset')) @opcache_reset();

echo "\nDone!\n";
