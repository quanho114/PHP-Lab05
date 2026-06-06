<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Lab05 App') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uriPath = explode('?', $uri)[0];
?>
<div class="app-layout">
    <header class="navbar">
        <div class="navbar-container">
            <!-- Brand Logo & Title -->
            <div class="navbar-brand">
                <div class="brand-logo">
                    <svg class="brand-logo-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                <span class="brand-text">Clinic CRM</span>
            </div>
            
            <!-- Horizontal Navigation Menu -->
            <nav class="navbar-menu">
                <a href="/" class="menu-item <?= $uriPath === '/' ? 'active' : '' ?>">
                    <span>Dashboard</span>
                </a>
                
                <a href="/patients" class="menu-item <?= (str_starts_with($uriPath, '/patients') && $uriPath !== '/patients/create') ? 'active' : '' ?>">
                    <span>Patients</span>
                </a>
                
                <a href="/patients/create" class="menu-item <?= $uriPath === '/patients/create' ? 'active' : '' ?>">
                    <span>Register Patient</span>
                </a>
                
                <a href="/appointments" class="menu-item <?= (str_starts_with($uriPath, '/appointments') && $uriPath !== '/appointments/create') ? 'active' : '' ?>">
                    <span>Appointments</span>
                </a>
                
                <a href="/appointments/create" class="menu-item <?= $uriPath === '/appointments/create' ? 'active' : '' ?>">
                    <span>Create Appointment</span>
                </a>
                
                <a href="/health" class="menu-item <?= $uriPath === '/health' ? 'active' : '' ?>">
                    <span>Health Metrics</span>
                </a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content Frame -->
    <div class="main-area">
        <main class="main-content">
            <?php if ($success = flash_get('success')): ?>
                <div class="alert success">
                    <span class="alert-icon">✓</span>
                    <span class="alert-message"><?= e($success) ?></span>
                </div>
            <?php endif; ?>
            
            <?= $content ?? '' ?>
        </main>
        
        <footer class="footer">
            <p>&copy; <?= date('Y') ?> - Clinic Control Center. Premium SaaS Experience.</p>
        </footer>
    </div>
</div>
</body>
</html>
