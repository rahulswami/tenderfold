<?php
/**
 * Tenderfold - Root Entry Proxy to Public Front Controller
 * Ensures seamless operation on shared hosting environments (Hostinger / cPanel / Apache).
 */

$publicIndex = __DIR__ . '/public/index.php';

if (file_exists($publicIndex)) {
    require_once $publicIndex;
} else {
    http_response_code(500);
    echo "Application front controller not found in /public.";
}

