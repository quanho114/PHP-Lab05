<?php
$context = $context ?? 'leads';
ob_start();
?>
<div class="error-page-container">
    <div class="error-page-header">
        <h1>Something went wrong</h1>
        <p class="subtitle">Production mode không hiển thị SQLSTATE hoặc đường dẫn file cho người dùng.</p>
    </div>

    <div class="safe-error-card">
        <h3>Sorry, we could not load <?= e($context) ?> right now.</h3>
        <p>Please try again later or contact the administrator.</p>
    </div>

    <div class="dev-note-card">
        <h4>Developer note:</h4>
        <p>Chi tiết lỗi được ghi vào log; giao diện chỉ hiển thị safe message.</p>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Something went wrong';
require __DIR__ . '/../layout.php';
