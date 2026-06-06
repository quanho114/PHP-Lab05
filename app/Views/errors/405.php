<?php ob_start(); ?>
<div class="error-container">
    <div class="error-badge warning">405</div>
    <h1>Method Not Allowed</h1>
    <p>The HTTP method used is not supported for this URL endpoint.</p>
    <a href="/" class="btn primary">Back to Dashboard</a>
</div>
<?php
$content = ob_get_clean();
$title = 'Method Not Allowed';
require __DIR__ . '/../layout.php';
