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

echo "Web PHP Version: " . phpversion() . "\n\n";

// 2. Find PHP 8.4 CLI
$phpCandidates = [
    '/usr/bin/php84',
    '/usr/bin/php8.4',
    '/opt/alt/php84/usr/bin/php',
    '/opt/alt/php83/usr/bin/php',
    '/usr/local/bin/php',
    '/usr/local/php84/bin/php',
];

$workingPhpCli = null;
foreach ($phpCandidates as $pc) {
    if (file_exists($pc)) {
        $out = [];
        @exec("$pc -v 2>&1", $out);
        echo "Found: $pc => " . ($out[0] ?? 'no output') . "\n";
        if (stripos($out[0] ?? '', '8.4') !== false || stripos($out[0] ?? '', '8.3') !== false) {
            $workingPhpCli = $pc;
        }
    }
}

// Also check which php
@exec("which php 2>&1", $whichPhp);
echo "Default 'php': " . implode('', $whichPhp) . "\n";
@exec("whereis php 2>&1", $whereis);
echo "whereis php: " . implode('', $whereis) . "\n\n";

// 3. If PHP CLI found, run migrations and seeds
if ($workingPhpCli) {
    echo "Running with $workingPhpCli:\n";
    $mig = [];
    @exec("cd " . escapeshellarg($basePath) . " && $workingPhpCli artisan migrate --force 2>&1", $mig);
    echo implode("\n", $mig) . "\n\n";

    $seed = [];
    @exec("cd " . escapeshellarg($basePath) . " && $workingPhpCli artisan db:seed --class=SiteSeeder --force 2>&1", $seed);
    echo implode("\n", $seed) . "\n\n";
} else {
    echo "No 8.4 CLI found directly in standard paths. Probing /opt/alt/:\n";
    $alt = glob('/opt/alt/*');
    if ($alt) echo implode("\n", $alt) . "\n";
}

// Clear views and cache
$viewFiles = glob($basePath . '/storage/framework/views/*.php');
foreach ($viewFiles as $vf) @unlink($vf);

if ($workingPhpCli) {
    @exec("cd " . escapeshellarg($basePath) . " && $workingPhpCli artisan view:clear 2>&1");
    @exec("cd " . escapeshellarg($basePath) . " && $workingPhpCli artisan config:clear 2>&1");
}

if (function_exists('opcache_reset')) @opcache_reset();

echo "\nDone!\n";
