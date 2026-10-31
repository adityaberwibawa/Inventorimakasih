<?php

declare(strict_types=1);

namespace App\Controllers;

use RuntimeException;

abstract class Controller
{
    /**
     * Render view dengan layout header/footer.
     *
     * @param array<string, mixed> $data
     */
    protected function view(string $view, array $data = []): void
    {
        $viewFile = APP_PATH . '/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException('View tidak ditemukan: ' . $view);
        }

        extract($data, EXTR_SKIP);

        require APP_PATH . '/Views/layouts/header.php';
        require $viewFile;
        require APP_PATH . '/Views/layouts/footer.php';
    }

    /**
     * Redirect lalu hentikan eksekusi (pola POST -> Redirect -> GET).
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    /**
     * Ambil nilai mentah dari $_POST.
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Ambil nilai string dari $_POST, sudah di-trim.
     */
    protected function postString(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;

        return is_string($value) ? trim($value) : $default;
    }

    /**
     * Tampilkan halaman 404 dengan layout aplikasi.
     */
    protected function notFound(string $message = 'Data yang Anda cari tidak ditemukan.'): never
    {
        http_response_code(404);

        $title = 'Halaman Tidak Ditemukan';
        $code = 404;

        require APP_PATH . '/Views/layouts/header.php';
        require APP_PATH . '/Views/errors/error.php';
        require APP_PATH . '/Views/layouts/footer.php';

        exit;
    }
}
