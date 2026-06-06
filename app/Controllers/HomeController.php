<?php
 
class HomeController
{
    private function db(): PDO
    {
        $config = require __DIR__ . '/../../config/database.php';
        return (new Database($config))->getConnection();
    }
 
    public function index(): void
    {
        $dbStatus = 'connected';
        $patientCount = 0;
        $appointmentCount = 0;
        $pendingCount = 0;
        $confirmedCount = 0;
        $completedCount = 0;
        $cancelledCount = 0;
 
        try {
            $pdo = $this->db();
            
            // Lấy tổng số bệnh nhân
            $stmt = $pdo->query("SELECT COUNT(*) AS total FROM patients");
            $patientCount = (int) ($stmt->fetch()['total'] ?? 0);
 
            // Lấy tổng số lịch hẹn và phân phối trạng thái
            $stmt = $pdo->query("SELECT COUNT(*) AS total FROM appointments");
            $appointmentCount = (int) ($stmt->fetch()['total'] ?? 0);

            $stmt = $pdo->query("SELECT status, COUNT(*) AS total FROM appointments GROUP BY status");
            $statusData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            $pendingCount = (int) ($statusData['pending'] ?? 0);
            $confirmedCount = (int) ($statusData['confirmed'] ?? 0);
            $completedCount = (int) ($statusData['completed'] ?? 0);
            $cancelledCount = (int) ($statusData['cancelled'] ?? 0);
        } catch (Exception $e) {
            $dbStatus = 'disconnected';
        }
 
        view('dashboard', compact(
            'dbStatus', 
            'patientCount', 
            'appointmentCount', 
            'pendingCount', 
            'confirmedCount', 
            'completedCount', 
            'cancelledCount'
        ));
    }
}
