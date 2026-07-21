<?php

declare(strict_types=1);

$publicPath = __DIR__ . '/public';
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestedFile = $publicPath . $requestPath;

if ($requestPath !== '/' && is_file($requestedFile)) {
    return false;
}

require $publicPath . '/index.php';