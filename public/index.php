<?php

declare(strict_types=1);

use App\Controllers\BarangController;
use App\Controllers\KategoriController;
use App\Core\Router;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('APP_DEBUG', true);

require_once APP_PATH . '/Core/Helper.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$router = new Router();

// -------------------------------------------------------------------------
// Rute Barang (dikerjakan oleh Anggota B)
// -------------------------------------------------------------------------
$router->get('/', [BarangController::class, 'index']);
$router->get('/barang', [BarangController::class, 'index']);
$router->get('/barang/tambah', [BarangController::class, 'create']);
$router->post('/barang/simpan', [BarangController::class, 'store']);
$router->get('/barang/edit/{id}', [BarangController::class, 'edit']);
$router->post('/barang/update', [BarangController::class, 'update']);
$router->post('/barang/hapus', [BarangController::class, 'destroy']);

// -------------------------------------------------------------------------
// Rute Kategori (Anggota A)
// -------------------------------------------------------------------------
$router->get('/kategori', [KategoriController::class, 'index']);
$router->get('/kategori/tambah', [KategoriController::class, 'create']);
$router->post('/kategori/simpan', [KategoriController::class, 'store']);
$router->get('/kategori/edit/{id}', [KategoriController::class, 'edit']);
$router->post('/kategori/update', [KategoriController::class, 'update']);
$router->post('/kategori/hapus', [KategoriController::class, 'destroy']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
