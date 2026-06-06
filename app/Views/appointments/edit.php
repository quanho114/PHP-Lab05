<?php ob_start(); ?>
<div class="form-header <?= !empty($isDuplicate) ? 'duplicate-header' : '' ?>">
    <h1><?= !empty($isDuplicate) ? 'Edit Appointment - Duplicate Appointment Code' : 'Edit Appointment' ?></h1>
    <p class="subtitle">Form này submit bằng POST /appointments/update, nếu thành công sẽ redirect về /appointments.</p>
</div>
 
<?php if (!empty($isDuplicate)): ?>
    <div class="alert-error-banner">
        Mã lịch hẹn này đã tồn tại. Vui lòng nhập mã lịch hẹn khác.
    </div>
<?php endif; ?>
 
<div class="form-container-grid <?= !empty($isDuplicate) ? 'duplicate-grid' : '' ?>">
    <form method="post" action="/appointments/update" class="form-card-horizontal">
        <input type="hidden" name="id" value="<?= e($id) ?>">
 
        <div class="form-row">
            <label for="appointment_code">Appointment Code</label>
            <div class="input-container">
                <input type="text" id="appointment_code" name="appointment_code" value="<?= e($old['appointment_code'] ?? '') ?>" class="<?= isset($errors['appointment_code']) ? 'input-error' : '' ?>" placeholder="APT-2026-0001">
                <?php if (!empty($errors['appointment_code'])): ?>
                    <p class="error">
                        <?= e($errors['appointment_code']) ?>
                        <?php if (!empty($isDuplicate)): ?>
                            <span class="constraint-note">appointment_code bị unique constraint chặn.</span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="patient_name">Patient Name</label>
            <div class="input-container">
                <input type="text" id="patient_name" name="patient_name" value="<?= e($old['patient_name'] ?? '') ?>" class="<?= isset($errors['patient_name']) ? 'input-error' : '' ?>" placeholder="Anna Nguyen">
                <?php if (!empty($errors['patient_name'])): ?><p class="error"><?= e($errors['patient_name']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="patient_email">Patient Email</label>
            <div class="input-container">
                <input type="email" id="patient_email" name="patient_email" value="<?= e($old['patient_email'] ?? '') ?>" class="<?= isset($errors['patient_email']) ? 'input-error' : '' ?>" placeholder="anna@example.com">
                <?php if (!empty($errors['patient_email'])): ?><p class="error"><?= e($errors['patient_email']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="appointment_date">Appointment Date</label>
            <div class="input-container">
                <input type="datetime-local" id="appointment_date" name="appointment_date" value="<?= e($old['appointment_date'] ?? '') ?>" class="<?= isset($errors['appointment_date']) ? 'input-error' : '' ?>">
                <?php if (!empty($errors['appointment_date'])): ?><p class="error"><?= e($errors['appointment_date']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="status">Status</label>
            <div class="input-container">
                <select id="status" name="status">
                    <?php foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $statusVal => $statusLbl): ?>
                        <option value="<?= e($statusVal) ?>" <?= ($old['status'] ?? 'pending') === $statusVal ? 'selected' : '' ?>>
                            <?= e($statusLbl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['status'])): ?><p class="error"><?= e($errors['status']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="note">Note</label>
            <div class="input-container">
                <textarea id="note" name="note" rows="4" placeholder="Nhập ghi chú cho cuộc hẹn..."><?= e($old['note'] ?? '') ?></textarea>
            </div>
        </div>
 
        <div class="form-actions-horizontal">
            <button class="btn primary" type="submit">Update Appointment</button>
            <a class="btn btn-cancel" href="/appointments">Back to Appointments</a>
        </div>
    </form>
 
    <div class="requirements-card">
        <?php if (!empty($isDuplicate)): ?>
            <h3>Database rule</h3>
            <div class="db-rule-container">
                <p class="db-rule-desc">Mã lịch hẹn được bảo vệ bằng ràng buộc khóa duy nhất ở cấp độ cơ sở dữ liệu:</p>
                <code class="db-rule-code">UNIQUE KEY unique_appointment_code (appointment_code)</code>
                <div class="db-rule-note">
                    <strong>Lưu ý:</strong> Mọi nỗ lực ghi đè hoặc tạo trùng giá trị này đều bị động cơ MySQL từ chối trực tiếp và trả về lỗi mã 1062.
                </div>
            </div>
        <?php else: ?>
            <h3>Form requirements</h3>
            <ul class="requirements-list">
                <li class="checked">Validate required fields</li>
                <li class="checked">Unique appointment code</li>
                <li class="checked">Patient email format check</li>
                <li class="checked">Prepared statement UPDATE</li>
                <li class="checked">Catch duplicate code</li>
                <li class="checked">PRG after success</li>
                <li class="checked">Keep old data when error</li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Edit Appointment';
require __DIR__ . '/../layout.php';
