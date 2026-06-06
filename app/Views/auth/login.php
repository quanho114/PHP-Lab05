<?php ob_start(); ?>
<div class="form-header">
    <h1>Clinic CRM Portal</h1>
    <p class="subtitle">Đăng nhập để quản trị hệ thống bệnh nhân và lịch hẹn phòng khám.</p>
</div>
 
<?php if (!empty($errors['general'])): ?>
    <div class="alert-error-banner" style="margin-bottom: 24px;">
        <?= e($errors['general']) ?>
    </div>
<?php endif; ?>
 
<div class="form-container-grid">
    <form method="post" action="/login" class="form-card-horizontal">
        <?= csrf_field() ?>
 
        <div class="form-row">
            <label for="email">Email</label>
            <div class="input-container">
                <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'input-error' : '' ?>" placeholder="staff@example.com" required>
                <?php if (!empty($errors['email'])): ?><p class="error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="password">Mật khẩu</label>
            <div class="input-container">
                <input type="password" id="password" name="password" class="<?= isset($errors['password']) ? 'input-error' : '' ?>" placeholder="••••••••" required>
                <?php if (!empty($errors['password'])): ?><p class="error"><?= e($errors['password']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-actions-horizontal">
            <button class="btn primary" type="submit">Đăng nhập</button>
        </div>
    </form>
 
    <div class="requirements-card">
        <h3>Demo accounts</h3>
        <p class="text-muted" style="margin-bottom: 16px;">Sử dụng tài khoản demo dưới đây để đăng nhập kiểm thử:</p>
        <div style="background: var(--bg-hover); padding: 12px 16px; border-radius: 8px; font-family: monospace; font-size: 14px; margin-bottom: 12px;">
            <strong>Staff Account:</strong><br>
            Email: <span class="text-primary">staff@example.com</span><br>
            Pass: <span class="text-primary">password123</span>
        </div>
        <div style="background: var(--bg-hover); padding: 12px 16px; border-radius: 8px; font-family: monospace; font-size: 14px;">
            <strong>Admin Account:</strong><br>
            Email: <span class="text-primary">admin@example.com</span><br>
            Pass: <span class="text-primary">password123</span>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Đăng nhập CRM';
require __DIR__ . '/../layout.php';
