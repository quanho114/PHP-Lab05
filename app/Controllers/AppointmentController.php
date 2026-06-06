<?php
 
class AppointmentController
{
    private function repository(): AppointmentRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new AppointmentRepository($pdo);
    }
 
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 5;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;
 
        $repo = $this->repository();
        $total = $repo->countAll($q, $status);
        $totalPages = max(1, (int) ceil($total / $perPage));
 
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }
 
        $appointments = $repo->getPaginated($q, $status, $perPage, $offset, $sort, $direction);
 
        view('appointments/index', compact('appointments', 'q', 'status', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }
 
    public function create(): void
    {
        $errors = [];
        $old = [
            'appointment_code' => '',
            'patient_name' => '',
            'patient_email' => '',
            'appointment_date' => '',
            'status' => 'pending',
            'note' => ''
        ];
        view('appointments/create', compact('errors', 'old'));
    }
 
    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
 
        if (!empty($errors)) {
            view('appointments/create', compact('errors', 'old'));
            return;
        }
 
        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Lịch hẹn mới đã được tạo thành công.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Mã lịch hẹn này đã tồn tại.';
            $isDuplicate = true;
            view('appointments/create', compact('errors', 'old', 'isDuplicate'));
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'appointments']);
        }
    }
 
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            view('errors/404');
            return;
        }
 
        $appointment = $this->repository()->findById($id);
        if (!$appointment) {
            http_response_code(404);
            view('errors/404');
            return;
        }
 
        $errors = [];
        // Format appointment_date to match datetime-local input (YYYY-MM-DDTHH:MM)
        if (!empty($appointment['appointment_date'])) {
            $appointment['appointment_date'] = date('Y-m-d\TH:i', strtotime($appointment['appointment_date']));
        }
        $old = $appointment;
        view('appointments/edit', compact('errors', 'old', 'id'));
    }
 
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            view('errors/404');
            return;
        }
 
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
 
        if (!empty($errors)) {
            view('appointments/edit', compact('errors', 'old', 'id'));
            return;
        }
 
        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Lịch hẹn đã được cập nhật thành công.');
            redirect('/appointments');
        } catch (DuplicateRecordException $e) {
            $errors['appointment_code'] = 'Mã lịch hẹn này đã tồn tại.';
            $isDuplicate = true;
            view('appointments/edit', compact('errors', 'old', 'id', 'isDuplicate'));
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'appointments']);
        }
    }
 
    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo "Yêu cầu không hợp lệ.";
            return;
        }
 
        try {
            $this->repository()->delete($id);
            flash_set('success', 'Đã hủy/xóa lịch hẹn thành công.');
            redirect('/appointments');
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'appointments']);
        }
    }
 
    private function validate(array $input): array
    {
        $values = [
            'appointment_code' => trim($input['appointment_code'] ?? ''),
            'patient_name' => trim($input['patient_name'] ?? ''),
            'patient_email' => trim($input['patient_email'] ?? ''),
            'appointment_date' => trim($input['appointment_date'] ?? ''),
            'status' => trim($input['status'] ?? 'pending'),
            'note' => trim($input['note'] ?? ''),
        ];
        $errors = [];
        $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
 
        if ($values['appointment_code'] === '') {
            $errors['appointment_code'] = 'Vui lòng nhập mã lịch hẹn.';
        }
        if ($values['patient_name'] === '') {
            $errors['patient_name'] = 'Vui lòng nhập tên bệnh nhân.';
        }
        if ($values['patient_email'] !== '' && !filter_var($values['patient_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['patient_email'] = 'Email không đúng định dạng.';
        }
        if ($values['appointment_date'] === '') {
            $errors['appointment_date'] = 'Vui lòng chọn ngày giờ hẹn.';
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }
 
        return ['values' => $values, 'errors' => $errors];
    }
 
    private function logError(Exception $e): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
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
    }
}
