<?php
$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;
$homeDir = '/home/u709314437';

header('Content-Type: text/plain; charset=utf-8');

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);

echo "=== CHECKING ONBOARDING & SETUP FILES ===\n\n";

// Check .pk_auth
$pkAuth = $basePath . '/.pk_auth';
if (file_exists($pkAuth)) {
    echo ".pk_auth content:\n" . file_get_contents($pkAuth) . "\n\n";
}

// Check website_onboarding_sop.html
$sop = $basePath . '/website_onboarding_sop.html';
if (file_exists($sop)) {
    echo "website_onboarding_sop.html (first 1000 chars):\n" . substr(file_get_contents($sop), 0, 1000) . "\n\n";
}

// Check .builds/logs
$buildLogs = glob($homeDir . '/domains/tenderfold.in/.builds/*');
foreach ($buildLogs as $bl) {
    echo "$bl:\n";
    if (is_file($bl)) {
        echo substr(file_get_contents($bl), 0, 1000) . "\n\n";
    }
}
