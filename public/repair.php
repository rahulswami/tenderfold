<?php
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;
$homeDir = '/home/u709314437';

header('Content-Type: text/plain; charset=utf-8');

echo "=== TENDERFOLD LOG & DATABASE INVESTIGATION ===\n\n";

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);
echo "Git Pull: " . implode("\n", $gitOutput) . "\n\n";

// 2. Inspect database.sqlite
$sqliteFile = $basePath . '/database/database.sqlite';
echo "SQLite file: $sqliteFile\n";
if (file_exists($sqliteFile)) {
    echo "  Exists: YES\n";
    echo "  Size: " . filesize($sqliteFile) . " bytes\n";
    echo "  Modified: " . date('Y-m-d H:i:s', filemtime($sqliteFile)) . "\n";
    if (filesize($sqliteFile) > 0) {
        try {
            $sqPdo = new PDO("sqlite:" . $sqliteFile);
            $sqTables = $sqPdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
            echo "  SQLite Tables (" . count($sqTables) . "): " . implode(', ', $sqTables) . "\n";
            if (in_array('users', $sqTables)) {
                $sqUsers = $sqPdo->query("SELECT count(*) FROM users")->fetchColumn();
                echo "  SQLite users count: $sqUsers\n";
            }
            if (in_array('services', $sqTables)) {
                $sqServ = $sqPdo->query("SELECT count(*) FROM services")->fetchColumn();
                echo "  SQLite services count: $sqServ\n";
            }
        } catch (\Throwable $e) {
            echo "  SQLite error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "  Exists: NO\n";
}
echo "\n";

// 3. Inspect storage/logs/laravel.log
$logFile = $basePath . '/storage/logs/laravel.log';
echo "Laravel Log: $logFile\n";
if (file_exists($logFile)) {
    echo "  Exists: YES, Size: " . filesize($logFile) . " bytes\n";
    echo "  Last 80 lines:\n";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -80);
    echo implode('', $lastLines);
} else {
    echo "  Exists: NO\n";
}
echo "\n";

// 4. Scan wp-config.php across all domains
echo "=== Scanning wp-config.php across all domains ===\n";
$wpConfigs = glob($homeDir . '/domains/*/public_html/wp-config.php');
if ($wpConfigs) {
    foreach ($wpConfigs as $wpc) {
        $dom = basename(dirname(dirname($wpc)));
        $content = @file_get_contents($wpc);
        if ($content) {
            preg_match("/define\(\s*['\"]DB_NAME['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $content, $dbm);
            preg_match("/define\(\s*['\"]DB_USER['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $content, $usm);
            preg_match("/define\(\s*['\"]DB_HOST['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $content, $hsm);
            echo "[$dom] DB=" . ($dbm[1] ?? 'unknown') . " | USER=" . ($usm[1] ?? 'unknown') . " | HOST=" . ($hsm[1] ?? 'unknown') . "\n";
        }
    }
}
echo "\n";

// 5. Look for any database files or sql files in the user home directory
echo "=== Searching for SQL or DB dumps in home tree ===\n";
$sqlFiles = glob($homeDir . '/*.sql');
$sqlFiles2 = glob($homeDir . '/domains/tenderfold.in/*.sql');
$sqlFiles3 = glob($homeDir . '/domains/tenderfold.in/public_html/*.sql');
$allSql = array_merge($sqlFiles ?: [], $sqlFiles2 ?: [], $sqlFiles3 ?: []);
foreach ($allSql as $sf) {
    echo "  $sf (" . filesize($sf) . " bytes)\n";
}

echo "\n=== Investigation Complete ===\n";
