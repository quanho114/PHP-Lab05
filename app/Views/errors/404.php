<?php ob_start(); ?>
<div class="error-container">
    <div class="error-badge">404</div>
    <h1>Page Not Found</h1>
    <p>The page you are looking for does not exist or has been moved.</p>
    <a href="/" class="btn primary">Back to Dashboard</a>
</div>
<?php
$content = ob_get_clean();
$title = 'Page Not Found';
require __DIR__ . '/../layout.php';
