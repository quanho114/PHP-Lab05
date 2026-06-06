<?php
session_start();
 
require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';
 
require __DIR__ . '/../app/Repositories/PatientRepository.php';
require __DIR__ . '/../app/Repositories/AppointmentRepository.php';
 
require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/PatientController.php';
require __DIR__ . '/../app/Controllers/AppointmentController.php';
 
$router = new Router();
 
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);
 
$router->get('/patients', [PatientController::class, 'index']);
$router->get('/patients/create', [PatientController::class, 'create']);
$router->post('/patients/store', [PatientController::class, 'store']);
$router->get('/patients/edit', [PatientController::class, 'edit']);
$router->post('/patients/update', [PatientController::class, 'update']);
$router->post('/patients/delete', [PatientController::class, 'delete']);
 
$router->get('/appointments', [AppointmentController::class, 'index']);
$router->get('/appointments/create', [AppointmentController::class, 'create']);
$router->post('/appointments/store', [AppointmentController::class, 'store']);
$router->get('/appointments/edit', [AppointmentController::class, 'edit']);
$router->post('/appointments/update', [AppointmentController::class, 'update']);
$router->post('/appointments/delete', [AppointmentController::class, 'delete']);
 
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    // Log detailed error to storage/logs/app.log
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . '/app.log';
    $message = sprintf(
        "[%s] ERROR: %s in %s:%d\nStack Trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    error_log($message, 3, $logFile);

    // Show safe 500 error page
    http_response_code(500);
    view('errors/500', ['context' => 'system']);
}
