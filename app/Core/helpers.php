<?php
 
if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
 
if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }
}
 
if (!function_exists('query_string')) {
    function query_string(array $params = []): string
    {
        $current = $_GET;
        foreach ($params as $key => $value) {
             if ($value === null || $value === '') {
                 unset($current[$key]);
             } else {
                 $current[$key] = $value;
             }
        }
        return http_build_query($current);
    }
}
 
if (!function_exists('flash_set')) {
    function flash_set(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }
}
 
if (!function_exists('flash_get')) {
    function flash_get(string $key): ?string
    {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}
 
if (!function_exists('view')) {
    function view(string $path, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $path . '.php';
    }
}
