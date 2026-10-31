<?php

declare(strict_types=1);

if (!function_exists('base_url')) {
    /**
     * Base URL aplikasi, mendukung akses lewat subfolder (mis. /Inventorimakasih/public).
     */
    function base_url(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $directory = str_replace('\\', '/', dirname($scriptName));

        if ($directory === '/' || $directory === '.' || $directory === '') {
            return '';
        }

        return rtrim($directory, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Bangun URL absolut dari base path aplikasi.
     */
    function url(string $path = ''): string
    {
        $path = ltrim($path, '/');

        if ($path === '') {
            return base_url() !== '' ? base_url() : '/';
        }

        return base_url() . '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Bangun URL untuk file di public/assets.
     */
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('e')) {
    /**
     * Escape output agar aman dari XSS.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    /**
     * Simpan pesan flash ke session untuk ditampilkan pada request berikutnya.
     */
    function flash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}

if (!function_exists('get_flashes')) {
    /**
     * Ambil dan hapus semua pesan flash dari session.
     *
     * @return array<int, array{type: string, message: string}>
     */
    function get_flashes(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            return [];
        }

        $flashes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return is_array($flashes) ? $flashes : [];
    }
}

if (!function_exists('rupiah')) {
    /**
     * Format angka menjadi Rupiah tanpa desimal.
     */
    function rupiah(float|int|string $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
}

if (!function_exists('kode_barang')) {
    /**
     * Format kode barang, misal "BRG-0001".
     */
    function kode_barang(int|string $id): string
    {
        return 'BRG-' . str_pad((string) (int) $id, 4, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Ambil (atau buat) token CSRF untuk sesi saat ini.
     */
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_input')) {
    /**
     * Hidden input berisi token CSRF untuk ditempelkan di setiap form POST.
     */
    function csrf_input(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_verify')) {
    /**
     * Verifikasi token CSRF pada setiap request POST.
     * Request ditolak dengan 419 jika token tidak ada atau tidak cocok.
     */
    function csrf_verify(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        $token = $_POST['_token'] ?? '';

        if (is_string($token) && $token !== '' && hash_equals(csrf_token(), $token)) {
            return;
        }

        http_response_code(419);

        $code = 419;
        $title = 'Permintaan Tidak Valid';
        $message = 'Token keamanan tidak valid atau sudah kedaluwarsa. Muat ulang halaman, lalu coba lagi.';

        require APP_PATH . '/Views/layouts/header.php';
        require APP_PATH . '/Views/errors/error.php';
        require APP_PATH . '/Views/layouts/footer.php';

        exit;
    }
}
