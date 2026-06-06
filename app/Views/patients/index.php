<?php ob_start(); ?>
<div class="index-container">
    <div class="index-header">
        <h1>Patient Directory</h1>
        <a class="btn primary" href="/patients/create">+ Register Patient</a>
    </div>
 
    <div class="data-card">
        <div class="data-card-header">
            <form method="get" action="/patients" class="toolbar">
                <input type="hidden" name="page" value="1">
                <div class="search-input-group">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search patient name, email or phone...">
                </div>
                <div class="filter-group">
                    <select name="gender" onchange="this.form.submit()">
                        <option value="">All Genders</option>
                        <?php foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $genderVal => $genderLbl): ?>
                            <option value="<?= e($genderVal) ?>" <?= $gender === $genderVal ? 'selected' : '' ?>><?= e($genderLbl) ?></option>
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
                    <a href="/patients?<?= e(query_string(['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'])) ?>" class="sortable <?= $sort === 'name' ? 'active ' . $direction : '' ?>">
                        Patient Name
                    </a>
                </th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>
                    <a href="/patients?<?= e(query_string(['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc'])) ?>" class="sortable <?= $sort === 'created_at' ? 'active ' . $direction : '' ?>">
                        Registered At
                    </a>
                </th>
                <th class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($patients)): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No patients found.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($patients as $patient): ?>
            <tr>
                <td><?= e($patient['id']) ?></td>
                <td class="font-semibold"><?= e($patient['name']) ?></td>
                <td><?= e($patient['email']) ?></td>
                <td><?= e($patient['phone'] ?: '-') ?></td>
                <td>
                    <span class="badge badge-<?= e($patient['gender']) ?>">
                        <?= ucfirst(e($patient['gender'])) ?>
                    </span>
                </td>
                <td class="text-muted"><?= e($patient['created_at']) ?></td>
                <td class="text-right">
                    <div class="actions-wrapper">
                        <a href="/patients/edit?id=<?= e($patient['id']) ?>" class="btn edit-btn">Edit</a>
                        <form method="post" action="/patients/delete" class="inline" onsubmit="return confirm('Delete this patient record?')">
                            <input type="hidden" name="id" value="<?= e($patient['id']) ?>">
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
                        <a href="/patients?<?= e(query_string(['page' => $page - 1])) ?>" class="page-link">Prev</a>
                    <?php endif; ?>

                    <div class="page-numbers">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="/patients?<?= e(query_string(['page' => $i])) ?>" class="page-link <?= $page === $i ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <?php if ($page < $totalPages): ?>
                        <a href="/patients?<?= e(query_string(['page' => $page + 1])) ?>" class="page-link">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Patient Directory';
require __DIR__ . '/../layout.php';
