<?php ob_start(); ?>
<div class="health-wrapper">
    <div class="dashboard-header-block">
        <h1 class="main-title">System Diagnostics</h1>
        <p class="subtitle-desc">Real-time database connectivity, environment properties, and client request metrics.</p>
    </div>

    <div class="health-grid">
        <!-- Pulse Status Panel -->
        <div class="health-status-card">
            <div class="status-summary">
                <div class="indicator-dot-large <?= $database === 'connected' ? 'active' : 'inactive' ?>"></div>
                <div class="status-meta">
                    <h2><?= $database === 'connected' ? 'Systems Operational' : 'Degraded Performance' ?></h2>
                    <p class="status-time">Last checked at <?= date('H:i:s') ?> (Server Local Time)</p>
                </div>
            </div>
        </div>

        <!-- Detail Diagnostics Grid -->
        <div class="diagnostic-grid">
            <!-- Database State -->
            <div class="diag-card">
                <h3>Database Engine</h3>
                <div class="diag-status <?= $database === 'connected' ? 'success' : 'danger' ?>">
                    <?= $database === 'connected' ? 'Connected' : 'Disconnected' ?>
                </div>
                <table class="diag-table">
                    <tr>
                        <td>Status</td>
                        <td><strong><?= strtoupper($database) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Driver</td>
                        <td><code>PDO MySQL</code></td>
                    </tr>
                    <tr>
                        <td>Host Server</td>
                        <td><code>db</code></td>
                    </tr>
                    <tr>
                        <td>Charset</td>
                        <td><code>utf8mb4</code></td>
                    </tr>
                </table>
            </div>

            <!-- Application Server Runtime -->
            <div class="diag-card">
                <h3>Application Runtime</h3>
                <div class="diag-status success">Active</div>
                <table class="diag-table">
                    <tr>
                        <td>PHP Version</td>
                        <td><code><?= PHP_VERSION ?></code></td>
                    </tr>
                    <tr>
                        <td>Interface (SAPI)</td>
                        <td><code><?= php_sapi_name() ?></code></td>
                    </tr>
                    <tr>
                        <td>Memory Limit</td>
                        <td><code><?= ini_get('memory_limit') ?></code></td>
                    </tr>
                    <tr>
                        <td>Execution Timeout</td>
                        <td><code><?= ini_get('max_execution_time') ?>s</code></td>
                    </tr>
                </table>
            </div>

            <!-- Request / Client Context -->
            <div class="diag-card">
                <h3>Request Context</h3>
                <div class="diag-status info">HTTP 200 OK</div>
                <table class="diag-table">
                    <tr>
                        <td>Client IP</td>
                        <td><code><?= $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1' ?></code></td>
                    </tr>
                    <tr>
                        <td>Request Method</td>
                        <td><code><?= $_SERVER['REQUEST_METHOD'] ?? 'GET' ?></code></td>
                    </tr>
                    <tr>
                        <td>Protocol</td>
                        <td><code><?= $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1' ?></code></td>
                    </tr>
                    <tr>
                        <td>Secure SSL</td>
                        <td><code><?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Active' : 'Inactive' ?></code></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'System Health Diagnostics';
require __DIR__ . '/layout.php';
