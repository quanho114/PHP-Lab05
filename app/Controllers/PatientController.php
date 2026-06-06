<?php
 
class PatientController
{
    private function repository(): PatientRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new PatientRepository($pdo);
    }
 
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $gender = trim($_GET['gender'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 5;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;
 
        $repo = $this->repository();
        $total = $repo->countAll($q, $gender);
        $totalPages = max(1, (int) ceil($total / $perPage));
 
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }
 
        $patients = $repo->getPaginated($q, $gender, $perPage, $offset, $sort, $direction);
 
        view('patients/index', compact('patients', 'q', 'gender', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }
 
    public function create(): void
    {
        $errors = [];
        $old = ['name' => '', 'email' => '', 'phone' => '', 'gender' => 'other', 'note' => ''];
        view('patients/create', compact('errors', 'old'));
    }
 
    public function store(): void
    {
        $data = $this->validate($_POST);
        $errors = $data['errors'];
        $old = $data['values'];
 
        if (!empty($errors)) {
            view('patients/create', compact('errors', 'old'));
            return;
        }
 
        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Bệnh nhân mới đã được đăng ký thành công.');
            redirect('/patients');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email này đã thuộc về một bệnh nhân khác.';
            $isDuplicate = true;
            view('patients/create', compact('errors', 'old', 'isDuplicate'));
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'patients']);
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
 
        $patient = $this->repository()->findById($id);
        if (!$patient) {
            http_response_code(404);
            view('errors/404');
            return;
        }
 
        $errors = [];
        $old = $patient;
        view('patients/edit', compact('errors', 'old', 'id'));
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
            view('patients/edit', compact('errors', 'old', 'id'));
            return;
        }
 
        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Thông tin bệnh nhân đã được cập nhật thành công.');
            redirect('/patients');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email này đã thuộc về một bệnh nhân khác.';
            $isDuplicate = true;
            view('patients/edit', compact('errors', 'old', 'id', 'isDuplicate'));
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'patients']);
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
            flash_set('success', 'Đã xóa thông tin bệnh nhân.');
            redirect('/patients');
        } catch (Exception $e) {
            $this->logError($e);
            http_response_code(500);
            view('errors/500', ['context' => 'patients']);
        }
    }
 
    private function validate(array $input): array
    {
        $values = [
            'name' => trim($input['name'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'gender' => trim($input['gender'] ?? 'other'),
            'note' => trim($input['note'] ?? ''),
        ];
        $errors = [];
        $allowedGenders = ['male', 'female', 'other'];
 
        if ($values['name'] === '') {
            $errors['name'] = 'Vui lòng nhập họ tên bệnh nhân.';
        }
        if ($values['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email bệnh nhân.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }
        if (!in_array($values['gender'], $allowedGenders, true)) {
            $errors['gender'] = 'Giới tính không hợp lệ.';
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
