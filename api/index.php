<?php 
 
use Illuminate\Http\Request; 
 
define('LARAVEL_START', microtime(true)); 
 
require _DIR_.'/../vendor/autoload.php'; 
 
$app = require _DIR_.'/../bootstrap/app.php'; 
 
$storage = sys_get_temp_dir().'/inventory-laravel'; 
foreach (['framework/cache/data', 'framework/sessions', 'framework/views'] as $dir) { 
    $path = $storage.'/'.$dir; 
    if (! is_dir($path)) { 
        mkdir($path, 0775, true); 
    } 
} 
 
$app->useStoragePath($storage);

// Vercel runs this file as /api/index.php, so Symfony would treat "/api" as the
// base path and strip it (turning /api/products into /products). Pretend the
// front controller lives at the web root so Laravel sees the full URI.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

$app->handleRequest(Request::capture());