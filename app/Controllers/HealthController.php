<?php
 
class HealthController
{
    public function index(): void
    {
        $database = 'failed';
        try {
            $config = require __DIR__ . '/../../config/database.php';
            $pdo = (new Database($config))->getConnection();
            $pdo->query('SELECT 1');
            $database = 'connected';
        } catch (Exception $e) {
            $database = 'failed';
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'text/html')) {
            if ($database === 'failed') {
                http_response_code(500);
            }
            // Render HTML view
            require __DIR__ . '/../Views/health.php';
        } else {
            header('Content-Type: application/json');
            if ($database === 'failed') {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'database' => 'failed']);
            } else {
                echo json_encode(['status' => 'ok', 'database' => 'connected']);
            }
        }
    }
}
