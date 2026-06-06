<?php
 
class AuthController
{
    private function db(): PDO
    {
        return Database::getInstance()->getConnection();
    }
 
    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            redirect('/');
        }
        $errors = [];
        $old = [];
        view('auth/login', compact('errors', 'old'));
    }
 
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];
 
        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập địa chỉ email.';
        }
        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu.';
        }
 
        if (empty($errors)) {
            $stmt = $this->db()->prepare("SELECT * FROM users WHERE email = :email AND status = 'active'");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
 
            if ($user && password_verify($password, $user['password_hash'])) {
                // Store user session info
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                flash_set('success', "Chào mừng trở lại, {$user['name']}!");
                redirect('/');
            } else {
                $errors['general'] = 'Email hoặc mật khẩu không chính xác.';
            }
        }
 
        $old = ['email' => $email];
        view('auth/login', compact('errors', 'old'));
    }
 
    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
        
        // Start a fresh session for flash/CSRF messages
        session_start();
        flash_set('success', 'Bạn đã đăng xuất thành công.');
        redirect('/login');
    }
}
