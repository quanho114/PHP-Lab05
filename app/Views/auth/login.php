<?php ob_start(); ?>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
            <h2>Clinic CRM</h2>
            <p>Đăng nhập để quản trị hệ thống phòng khám</p>
        </div>
        
        <?php if (!empty($errors['general'])): ?>
            <div class="alert-error-banner">
                <?= e($errors['general']) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/login" class="login-form">
            <?= csrf_field() ?>
            
            <div class="login-field">
                <label for="email">Email</label>
                <div class="input-with-icon">
                    <span class="input-icon">✉️</span>
                    <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'input-error' : '' ?>" placeholder="staff@example.com" required>
                </div>
                <?php if (!empty($errors['email'])): ?><p class="error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>
            
            <div class="login-field">
                <label for="password">Mật khẩu</label>
                <div class="input-with-icon">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" class="<?= isset($errors['password']) ? 'input-error' : '' ?>" placeholder="••••••••" required>
                </div>
                <?php if (!empty($errors['password'])): ?><p class="error"><?= e($errors['password']) ?></p><?php endif; ?>
            </div>
            
            <button type="submit" class="btn primary btn-block">Đăng nhập</button>
        </form>
        
        <div class="demo-accounts-box">
            <h4>Demo Accounts (Copy & Paste)</h4>
            <div class="demo-item" onclick="fillCreds('staff@example.com', 'password123')">
                <span>Staff: staff@example.com</span>
                <span class="click-badge">Click to autofill</span>
            </div>
            <div class="demo-item" onclick="fillCreds('admin@example.com', 'password123')">
                <span>Admin: admin@example.com</span>
                <span class="click-badge">Click to autofill</span>
            </div>
        </div>
    </div>
</div>

<script>
function fillCreds(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
<?php
$content = ob_get_clean();
$title = 'Đăng nhập CRM';
require __DIR__ . '/../layout.php';
