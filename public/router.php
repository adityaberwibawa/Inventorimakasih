<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (!is_string($path) || $path === '') {
    $path = '/';
}

$publicPath = realpath(__DIR__);

if ($publicPath === false) {
    require __DIR__ . '/index.php';
    return;
}

$file = realpath(__DIR__ . $path);

if (
    $path !== '/'
    && $file !== false
    && str_starts_with($file, $publicPath . DIRECTORY_SEPARATOR)
    && is_file($file)
) {
    return false;
}

require __DIR__ . '/index.php';
