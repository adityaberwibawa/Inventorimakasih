<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

final class Router
{
    /**
     * @var array<int, array{
     *     method: string,
     *     path: string,
     *     pattern: string,
     *     handler: array{0: class-string, 1: string}
     * }>
     */
    private array $routes = [];

    /**
     * @param array{0: class-string, 1: string} $handler
     */
    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     */
    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = $this->currentPath($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches) !== 1) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            $this->call($route['handler'], $params);

            return;
        }

        $this->abort(404, 'Halaman yang Anda cari tidak ditemukan.');
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param array<string, string> $params
     */
    private function call(array $handler, array $params): void
    {
        [$controllerClass, $action] = $handler;

        if (!class_exists($controllerClass)) {
            $this->abort(404, 'Halaman belum tersedia. Fitur ini mungkin sedang dikerjakan.');
            return;
        }

        try {
            $controller = new $controllerClass();

            if (!method_exists($controller, $action)) {
                $this->abort(404, 'Halaman yang Anda cari tidak ditemukan.');
                return;
            }

            $controller->{$action}(...array_values($params));
        } catch (Throwable $error) {
            $this->serverError($error);
        }
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     */
    private function add(string $method, string $path, array $handler): void
    {
        $path = $this->normalize($path);
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);

        if ($pattern === null) {
            return;
        }

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    private function currentPath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $path = rawurldecode($path);
        $basePath = $this->basePath();

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return $this->normalize($path);
    }

    private function basePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $directory = str_replace('\\', '/', dirname($scriptName));

        if ($directory === '/' || $directory === '.' || $directory === '') {
            return '';
        }

        return rtrim($directory, '/');
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '' ? '/' : $path;
    }

    private function abort(int $code, string $message): never
    {
        http_response_code($code);

        $title = $code === 404 ? 'Halaman Tidak Ditemukan' : 'Terjadi Kesalahan';

        require APP_PATH . '/Views/layouts/header.php';
        require APP_PATH . '/Views/errors/error.php';
        require APP_PATH . '/Views/layouts/footer.php';

        exit;
    }

    private function serverError(Throwable $error): never
    {
        http_response_code(500);

        $code = 500;
        $title = 'Terjadi Kesalahan';
        $message = 'Terjadi kesalahan pada server. Silakan coba lagi.';

        if (defined('APP_DEBUG') && APP_DEBUG) {
            $message = $error->getMessage();
        }

        require APP_PATH . '/Views/layouts/header.php';
        require APP_PATH . '/Views/errors/error.php';
        require APP_PATH . '/Views/layouts/footer.php';

        exit;
    }
}
