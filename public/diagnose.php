<?php
/**
 * Standalone Laravel Environment Diagnostic Script
 * Place this in the public/ folder (public/diagnose.php) and visit /diagnose.php
 */

header('Content-Type: text/html; charset=utf-8');

function checkRequirement($name, $status, $message = '') {
    $icon = $status ? '🟢 SUCCESS' : '🔴 FAILED';
    $color = $status ? '#10b981' : '#ef4444';
    echo "<div style='margin-bottom: 10px; padding: 10px; border-left: 5px solid {$color}; background-color: #f9fafb; font-family: monospace;'>";
    echo "<strong>{$icon} - {$name}</strong><br>";
    if ($message) {
        echo "<span style='color: #4b5563; font-size: 13px;'>Detail: " . htmlspecialchars($message) . "</span>";
    }
    echo "</div>";
    return $status;
}

echo "<!DOCTYPE html><html><head><title>Laravel Deployment Diagnostics</title><meta name='viewport' content='width=device-width, initial-scale=1.0'></head><body style='padding: 20px; font-family: system-ui, sans-serif; max-width: 800px; margin: 0 auto; color: #1f2937;'>";
echo "<h2>System Diagnostics</h2>";

// 1. Check PHP Version
$phpVersion = PHP_VERSION;
$versionOk = version_compare($phpVersion, '8.2.0', '>=');
checkRequirement("PHP Version", $versionOk, "Current PHP version is " . $phpVersion . " (Laravel 11+ requires PHP >= 8.2, your composer.json requires PHP >= 8.3)");

// 2. Check PHP Extensions
$requiredExtensions = ['ctype', 'curl', 'dom', 'fileinfo', 'filter', 'hash', 'mbstring', 'openssl', 'pcre', 'pdo', 'pdo_mysql', 'session', 'tokenizer', 'xml'];
$missingExtensions = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}
$extensionsOk = empty($missingExtensions);
checkRequirement(
    "Required PHP Extensions", 
    $extensionsOk, 
    $extensionsOk ? "All required extensions are loaded." : "Missing extensions: " . implode(', ', $missingExtensions)
);

// 3. Check for .env file
$envPath = dirname(__DIR__) . '/.env';
$envExists = file_exists($envPath);
$envReadable = $envExists ? is_readable($envPath) : false;
checkRequirement(
    "Checking .env File", 
    $envExists && $envReadable, 
    !$envExists ? "The .env file was not found at: " . $envPath : ($envReadable ? ".env file is present and readable." : ".env file is present but NOT readable.")
);

// 4. Parse .env file if readable and check values
$envData = [];
if ($envReadable) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_pad(explode('=', $line, 2), 2, null);
        if ($name) {
            $envData[trim($name)] = trim($value);
        }
    }
    
    $appKey = isset($envData['APP_KEY']) ? $envData['APP_KEY'] : null;
    checkRequirement(
        "APP_KEY check",
        !empty($appKey),
        !empty($appKey) ? "APP_KEY is set: " . substr($appKey, 0, 15) . "..." : "APP_KEY is not defined in .env!"
    );
}

// 5. Check vendor directory
$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
$vendorExists = file_exists($vendorAutoload);
checkRequirement(
    "Composer Vendor Directory", 
    $vendorExists, 
    $vendorExists ? "vendor/autoload.php exists." : "vendor/autoload.php does not exist. Run 'composer install' on the server."
);

// 6. Check writable paths
$pathsToCheck = [
    'storage' => dirname(__DIR__) . '/storage',
    'storage/app' => dirname(__DIR__) . '/storage/app',
    'storage/framework' => dirname(__DIR__) . '/storage/framework',
    'storage/framework/cache' => dirname(__DIR__) . '/storage/framework/cache',
    'storage/framework/sessions' => dirname(__DIR__) . '/storage/framework/sessions',
    'storage/framework/views' => dirname(__DIR__) . '/storage/framework/views',
    'storage/logs' => dirname(__DIR__) . '/storage/logs',
    'bootstrap/cache' => dirname(__DIR__) . '/bootstrap/cache',
];

$permissionsOk = true;
foreach ($pathsToCheck as $name => $path) {
    if (!file_exists($path)) {
        $permissionsOk = false;
        checkRequirement("Directory Existence: {$name}", false, "Directory does not exist at: {$path}");
    } else {
        $isWritable = is_writable($path);
        if (!$isWritable) {
            $permissionsOk = false;
        }
        checkRequirement(
            "Directory Writable: {$name}", 
            $isWritable, 
            $isWritable ? "Directory is writable. Permissions: " . substr(sprintf('%o', fileperms($path)), -4) : "Directory is NOT writable! Try running chmod -R 775 on this folder."
        );
    }
}

// 7. Check database connection
if ($envReadable) {
    $dbHost = isset($envData['DB_HOST']) ? $envData['DB_HOST'] : '';
    $dbPort = isset($envData['DB_PORT']) ? $envData['DB_PORT'] : '3306';
    $dbDatabase = isset($envData['DB_DATABASE']) ? $envData['DB_DATABASE'] : '';
    $dbUsername = isset($envData['DB_USERNAME']) ? $envData['DB_USERNAME'] : '';
    $dbPassword = isset($envData['DB_PASSWORD']) ? trim($envData['DB_PASSWORD'], '"\'') : '';
    $dbConnection = isset($envData['DB_CONNECTION']) ? $envData['DB_CONNECTION'] : 'mysql';
    
    if ($dbConnection === 'mysql') {
        try {
            $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbDatabase};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5, // 5 second timeout
            ];
            $pdo = new PDO($dsn, $dbUsername, $dbPassword, $options);
            checkRequirement("Database Connection", true, "Successfully connected to the database '{$dbDatabase}' on '{$dbHost}'");
        } catch (Exception $e) {
            checkRequirement("Database Connection", false, "Failed connecting to database: " . $e->getMessage());
        }
    } else {
        checkRequirement("Database Connection", false, "Unsupported database driver in .env: " . $dbConnection);
    }
}

// 8. Try booting bootstrap/app.php in a try-catch to find syntax/boot errors
if ($vendorExists) {
    require_once $vendorAutoload;
    echo "<h3>Attempting to boot Laravel application...</h3>";
    try {
        // Disable output buffering to catch early errors
        ob_start();
        $app = require_once dirname(__DIR__) . '/bootstrap/app.php';
        ob_end_clean();
        checkRequirement("Laravel Application Boot", true, "Laravel successfully initialized.");
    } catch (Throwable $e) {
        ob_end_clean();
        checkRequirement("Laravel Application Boot", false, "Error during boot: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString());
    }
}

echo "</body></html>";
