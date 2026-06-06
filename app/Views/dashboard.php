<?php ob_start(); ?>
<div class="dashboard-wrapper compact">
    <div class="dashboard-header-block compact">
        <h1 class="main-title compact">Clinic Control Center</h1>
        <p class="subtitle-desc compact">Patient Registry & Appointment CRM (Lab 05 Database CRUD & Repository Architecture)</p>
    </div>
 
    <div class="dashboard-bento compact">
        <!-- Tile 1: Live CRM Performance Stats -->
        <div class="bento-card col-span-3 stats-main-card compact">
            <div class="bento-card-header compact">
                <h2>Clinic Volume & Schedule</h2>
            </div>
            
            <div class="stats-overview-grid compact">
                <div class="stat-large-box patients">
                    <span class="stat-val"><?= e($patientCount) ?></span>
                    <span class="stat-lbl">Registered Patients</span>
                </div>
                <div class="stat-large-box appointments">
                    <span class="stat-val"><?= e($appointmentCount) ?></span>
                    <span class="stat-lbl">Total Appointments</span>
                </div>
            </div>

            <div class="status-distribution-block compact">
                <h3>Schedule Distribution</h3>
                <div class="status-distribution-grid compact">
                    <div class="status-pill-box pending">
                        <span class="status-num"><?= e($pendingCount) ?></span>
                        <span class="status-lbl">Pending</span>
                    </div>
                    <div class="status-pill-box confirmed">
                        <span class="status-num"><?= e($confirmedCount) ?></span>
                        <span class="status-lbl">Confirmed</span>
                    </div>
                    <div class="status-pill-box completed">
                        <span class="status-num"><?= e($completedCount) ?></span>
                        <span class="status-lbl">Completed</span>
                    </div>
                    <div class="status-pill-box cancelled">
                        <span class="status-num"><?= e($cancelledCount) ?></span>
                        <span class="status-lbl">Cancelled</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tile 2: Database Connection Health -->
        <div class="bento-card col-span-2 db-health-card compact">
            <div class="bento-card-header compact">
                <h2>Database Health</h2>
            </div>
            <div class="bento-card-body compact">
                <div class="health-indicator-row compact">
                    <span class="indicator-dot <?= $dbStatus === 'connected' ? 'active' : 'inactive' ?>"></span>
                    <span class="indicator-txt"><?= $dbStatus === 'connected' ? 'MySQL Connection Stable' : 'MySQL Disconnected' ?></span>
                </div>
                <ul class="db-details-list compact">
                    <li><strong>PDO Driver:</strong> <code>mysql:host=db;charset=utf8mb4</code></li>
                    <li><strong>Error Mode:</strong> <code>ERRMODE_EXCEPTION</code></li>
                    <li><strong>Prep Emulation:</strong> <code>false</code> (Prepared Statements Active)</li>
                </ul>
            </div>
        </div>

        <!-- Tile 3: System Architecture Lifecyle -->
        <div class="bento-card col-span-3 flow-card compact">
            <div class="bento-card-header compact">
                <h2>Request Lifecycle (MVC Model)</h2>
            </div>
            <div class="bento-card-body compact">
                <div class="lifecycle-flow-wrapper compact">
                    <!-- Symmetrical horizontal connecting line -->
                    <div class="flow-track-line"></div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </div>
                        <span class="node-title">Browser</span>
                    </div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                            </svg>
                        </div>
                        <span class="node-title">index.php</span>
                    </div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                            </svg>
                        </div>
                        <span class="node-title">Router</span>
                    </div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="4" width="16" height="16" rx="2"/>
                                <rect x="9" y="9" width="6" height="6"/>
                                <line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/>
                                <line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>
                                <line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/>
                                <line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/>
                            </svg>
                        </div>
                        <span class="node-title">Controller</span>
                    </div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <span class="node-title">Repository</span>
                    </div>
                    
                    <div class="flow-node compact">
                        <div class="node-icon-wrapper compact">
                            <svg class="node-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                                <path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/>
                            </svg>
                        </div>
                        <span class="node-title">MySQL</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tile 4: Query Optimization & Indexes -->
        <div class="bento-card col-span-2 perf-card compact">
            <div class="bento-card-header compact">
                <h2>Index Optimization</h2>
            </div>
            <div class="bento-card-body compact">
                <p class="bento-card-desc compact">Active indices optimize speed and prevent full scans:</p>
                <div class="index-badge-group compact">
                    <code class="index-badge">UNIQUE(email)</code>
                    <code class="index-badge">UNIQUE(appointment_code)</code>
                    <code class="index-badge">INDEX(phone)</code>
                    <code class="index-badge">INDEX(status, created_at)</code>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
