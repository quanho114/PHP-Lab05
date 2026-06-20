<?php ob_start(); ?>
<div class="index-container">
    <div class="index-header">
        <h1>Appointment Management</h1>
        <a class="btn primary" href="/appointments/create">+ Create Appointment</a>
    </div>
 
    <div class="data-card">
        <div class="data-card-header">
            <form method="get" action="/appointments" class="toolbar">
                <input type="hidden" name="page" value="1">
                <div class="search-input-group">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search code, patient name or email...">
                </div>
                <div class="filter-group">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <?php foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $statusVal => $statusLbl): ?>
                            <option value="<?= e($statusVal) ?>" <?= $status === $statusVal ? 'selected' : '' ?>><?= e($statusLbl) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="sort" value="<?= e($sort) ?>">
                <input type="hidden" name="direction" value="<?= e($direction) ?>">
                <button type="submit" class="btn btn-search">Search</button>
            </form>
        </div>
        <div class="table-core">
            <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>
                    <a href="/appointments?<?= e(query_string(['sort' => 'appointment_code', 'direction' => ($sort === 'appointment_code' && $direction === 'asc') ? 'desc' : 'asc'])) ?>" class="sortable <?= $sort === 'appointment_code' ? 'active ' . $direction : '' ?>">
                        Appointment Code
                    </a>
                </th>
                <th>Patient Name</th>
                <th>Email</th>
                <th>
                    <a href="/appointments?<?= e(query_string(['sort' => 'appointment_date', 'direction' => ($sort === 'appointment_date' && $direction === 'asc') ? 'desc' : 'asc'])) ?>" class="sortable <?= $sort === 'appointment_date' ? 'active ' . $direction : '' ?>">
                        Appointment Date
                    </a>
                </th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($appointments)): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No appointments found.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($appointments as $appt): ?>
            <tr>
                <td><?= e($appt['id']) ?></td>
                <td class="font-semibold text-primary"><?= e($appt['appointment_code']) ?></td>
                <td class="font-semibold"><?= e($appt['patient_name']) ?></td>
                <td><?= e($appt['patient_email'] ?: '-') ?></td>
                <td><?= e($appt['appointment_date']) ?></td>
                <td>
                    <span class="badge badge-<?= e($appt['status']) ?>">
                        <?= ucfirst(e($appt['status'])) ?>
                    </span>
                </td>
                <td class="text-right">
                    <div class="actions-wrapper">
                        <a href="/appointments/edit?id=<?= e($appt['id']) ?>" class="btn edit-btn">Edit</a>
                        <form method="post" action="/appointments/delete" class="inline" onsubmit="return confirm('Cancel/delete this appointment?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($appt['id']) ?>">
                            <button type="submit" class="link danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?>
            <div class="data-card-footer">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="/appointments?<?= e(query_string(['page' => $page - 1])) ?>" class="page-link">Prev</a>
                    <?php else: ?>
                        <span class="page-link disabled" style="opacity: 0.5; pointer-events: none;">Prev</span>
                    <?php endif; ?>

                    <div class="page-numbers">
                        <?php
                        $maxVisible = 10;
                        $half = floor($maxVisible / 2);
                        $startPage = max(1, $page - $half);
                        $endPage = min($totalPages, $startPage + $maxVisible - 1);
                        if ($endPage - $startPage + 1 < $maxVisible) {
                            $startPage = max(1, $endPage - $maxVisible + 1);
                        }
                        ?>
                        <?php if ($startPage > 1): ?>
                            <a href="/appointments?<?= e(query_string(['page' => 1])) ?>" class="page-link">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="page-ellipsis">...</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="/appointments?<?= e(query_string(['page' => $i])) ?>" class="page-link <?= (int)$page == $i ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <span class="page-ellipsis">...</span>
                            <?php endif; ?>
                            <a href="/appointments?<?= e(query_string(['page' => $totalPages])) ?>" class="page-link"><?= $totalPages ?></a>
                        <?php endif; ?>
                    </div>

                    <?php if ($page < $totalPages): ?>
                        <a href="/appointments?<?= e(query_string(['page' => $page + 1])) ?>" class="page-link">Next</a>
                    <?php else: ?>
                        <span class="page-link disabled" style="opacity: 0.5; pointer-events: none;">Next</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Appointment Management';
require __DIR__ . '/../layout.php';
