<?php
$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;

header('Content-Type: text/plain; charset=utf-8');

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);

$logFile = $basePath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    // Find all occurrences of "[2026-"
    preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] [^\n]+/i', $content, $matches);
    echo "Total log entries found: " . count($matches[0]) . "\n\n";
    $recentHeaders = array_slice($matches[0], -20);
    foreach ($recentHeaders as $rh) {
        echo "$rh\n";
    }
    
    // Also print the last 200 lines of laravel.log
    echo "\n=== Last 200 lines of laravel.log ===\n";
    $lines = file($logFile);
    echo implode('', array_slice($lines, -200));
} else {
    echo "No log file\n";
}
