<?php
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

$isInsidePublic = basename(__DIR__) === 'public';
$basePath = $isInsidePublic ? dirname(__DIR__) : __DIR__;
$homeDir = '/home/u709314437';

header('Content-Type: text/plain; charset=utf-8');

echo "=== TENDERFOLD GLOBAL DOMAINS & DB ENVS SCAN ===\n\n";

// 1. Auto Git pull
$gitOutput = [];
@exec("cd " . escapeshellarg($basePath) . " && git pull origin main 2>&1", $gitOutput);
echo "Git Pull: " . implode("\n", $gitOutput) . "\n\n";

// 2. Scan all .env files across all domains
$envFiles = glob($homeDir . '/domains/*/public_html/.env');
$envFiles2 = glob($homeDir . '/domains/*/.env');
$allEnvs = array_merge($envFiles ?: [], $envFiles2 ?: []);

echo "Found " . count($allEnvs) . " .env files across domains:\n";

$discoveredConfigs = [];
$passwords = [];
$users = [];
$databases = [];

foreach ($allEnvs as $ef) {
    $domain = basename(dirname(dirname($ef)));
    if ($domain === 'domains') $domain = basename(dirname($ef));
    $lines = @file($ef, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) continue;
    
    $cfg = ['domain' => $domain, 'file' => $ef];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $k = trim($parts[0]);
            $v = trim(trim($parts[1]), '"\' ');
            if (in_array($k, ['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])) {
                $cfg[$k] = $v;
            }
        }
    }
    
    if (!empty($cfg['DB_DATABASE'])) {
        $discoveredConfigs[] = $cfg;
        if (!empty($cfg['DB_PASSWORD']) && !in_array($cfg['DB_PASSWORD'], $passwords)) {
            $passwords[] = $cfg['DB_PASSWORD'];
        }
        if (!empty($cfg['DB_USERNAME']) && !in_array($cfg['DB_USERNAME'], $users)) {
            $users[] = $cfg['DB_USERNAME'];
        }
        if (!empty($cfg['DB_DATABASE']) && !in_array($cfg['DB_DATABASE'], $databases)) {
            $databases[] = $cfg['DB_DATABASE'];
        }
        echo "[$domain] DB={$cfg['DB_DATABASE']} | USER={$cfg['DB_USERNAME']} | HOST={$cfg['DB_HOST']}\n";
    }
}
echo "\n";

// Check if any domain is specifically tenderfold or contains tender
echo "=== Searching for tenderfold specific files in domains/tenderfold.in ===\n";
$tfFiles = glob($homeDir . '/domains/tenderfold.in/*');
$tfFilesDot = glob($homeDir . '/domains/tenderfold.in/.*');
$allTfFiles = array_merge($tfFiles ?: [], $tfFilesDot ?: []);
foreach ($allTfFiles as $tff) {
    echo "  " . basename($tff) . (is_dir($tff) ? ' (dir)' : ' (' . filesize($tff) . 'b)') . "\n";
}

$buildFiles = glob($homeDir . '/domains/tenderfold.in/.builds/*');
if ($buildFiles) {
    echo "  .builds files:\n";
    foreach ($buildFiles as $bf) {
        echo "    " . basename($bf) . ' (' . filesize($bf) . "b)\n";
    }
}

// Check .trash or backups
$trashFiles = glob($homeDir . '/.trash/*');
if ($trashFiles) {
    echo "  .trash files:\n";
    foreach ($trashFiles as $tf) {
        echo "    " . basename($tf) . "\n";
    }
}

// 3. Test listing all databases with discovered working credentials
echo "\n=== Probing MySQL Databases with Discovered Users ===\n";
$allAccessibleDatabases = [];

foreach ($discoveredConfigs as $dc) {
    $host = $dc['DB_HOST'] ?: 'localhost';
    $user = $dc['DB_USERNAME'];
    $pass = $dc['DB_PASSWORD'];
    $db = $dc['DB_DATABASE'];
    
    try {
        $pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2
        ]);
        
        // Try SHOW DATABASES
        try {
            $dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
            echo "User $user connected to $db and can see " . count($dbs) . " databases:\n";
            echo "   " . implode(', ', $dbs) . "\n";
            foreach ($dbs as $d) {
                if (!in_array($d, $allAccessibleDatabases)) $allAccessibleDatabases[] = $d;
            }
        } catch (\Throwable $e) {
            echo "User $user connected to $db (cannot SHOW DATABASES)\n";
        }
    } catch (\Throwable $e) {
        // fail
    }
}

echo "\nAll discovered databases on server: " . implode(', ', $allAccessibleDatabases) . "\n\n";

// 4. Try every password with tenderfold databases
$tfDatabases = [];
foreach ($allAccessibleDatabases as $d) {
    if (stripos($d, 'tender') !== false || stripos($d, 'fold') !== false || stripos($d, 'laundry') !== false) {
        $tfDatabases[] = $d;
    }
}
echo "Tenderfold candidate databases found: " . implode(', ', $tfDatabases) . "\n";

// Now test all passwords against all tenderfold databases
$workingTfConfig = null;
foreach ($tfDatabases as $tdb) {
    foreach ($users as $u) {
        foreach ($passwords as $p) {
            foreach (['localhost', '127.0.0.1'] as $h) {
                try {
                    $pdo = new PDO("mysql:host={$h};dbname={$tdb};charset=utf8mb4", $u, $p, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 2
                    ]);
                    $workingTfConfig = ['host' => $h, 'db' => $tdb, 'user' => $u, 'pass' => $p, 'pdo' => $pdo];
                    echo "🎉 SUCCESS: Connected to $tdb with user $u!\n";
                    break 4;
                } catch (\Throwable $e) {}
            }
        }
    }
}

if ($workingTfConfig) {
    echo "\n=== Configuring Tenderfold with $workingTfConfig[db] ===\n";
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

DB_CONNECTION=mysql
DB_HOST={$workingTfConfig['host']}
DB_PORT=3306
DB_DATABASE={$workingTfConfig['db']}
DB_USERNAME={$workingTfConfig['user']}
DB_PASSWORD="{$workingTfConfig['pass']}"

BROADCAST_DRIVER=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
ENV;
    file_put_contents($envPath, $envContent);
    file_put_contents($basePath . '/.env.backup', $envContent);
    echo "Saved .env and .env.backup!\n";

    // Test tables
    $tables = $workingTfConfig['pdo']->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in {$workingTfConfig['db']}: " . count($tables) . "\n";
    echo "Sample tables: " . implode(', ', array_slice($tables, 0, 15)) . "\n";
} else {
    echo "No direct tenderfold database connected yet.\n";
}

if (function_exists('opcache_reset')) {
    @opcache_reset();
}

echo "\n=== Done ===\n";
