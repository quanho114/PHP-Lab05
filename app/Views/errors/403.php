<?php ob_start(); ?>
<div class="error-container">
    <div class="error-badge warning">403</div>
    <h1>Access Forbidden</h1>
    <p>Yêu cầu của bạn bị từ chối do thiếu hoặc sai mã xác thực CSRF (CSRF Token).</p>
    <a href="/" class="btn primary">Back to Dashboard</a>
</div>
<?php
$content = ob_get_clean();
$title = 'Access Forbidden';
require __DIR__ . '/../layout.php';
