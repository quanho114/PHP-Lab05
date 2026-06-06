<?php ob_start(); ?>
<div class="form-header <?= !empty($isDuplicate) ? 'duplicate-header' : '' ?>">
    <h1><?= !empty($isDuplicate) ? 'Register Patient - Duplicate Email' : 'Register Patient' ?></h1>
    <p class="subtitle">Form này submit bằng POST /patients/store, nếu thành công sẽ redirect về /patients.</p>
</div>
 
<?php if (!empty($isDuplicate)): ?>
    <div class="alert-error-banner">
        Email này đã tồn tại trong hệ thống. Vui lòng dùng email khác.
    </div>
<?php endif; ?>
 
<div class="form-container-grid <?= !empty($isDuplicate) ? 'duplicate-grid' : '' ?>">
    <form method="post" action="/patients/store" class="form-card-horizontal">
        <?= csrf_field() ?>
        <div class="form-row">
            <label for="name">Name</label>
            <div class="input-container">
                <input type="text" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'input-error' : '' ?>" placeholder="Anna Nguyen">
                <?php if (!empty($errors['name'])): ?><p class="error"><?= e($errors['name']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="email">Email</label>
            <div class="input-container">
                <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'input-error' : '' ?>" placeholder="anna@example.com">
                <?php if (!empty($errors['email'])): ?><p class="error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="phone">Phone</label>
            <div class="input-container">
                <input type="text" id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>" class="<?= isset($errors['phone']) ? 'input-error' : '' ?>" placeholder="0909000001">
                <?php if (!empty($errors['phone'])): ?><p class="error"><?= e($errors['phone']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="gender">Gender</label>
            <div class="input-container">
                <select id="gender" name="gender">
                    <?php foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $genderVal => $genderLbl): ?>
                        <option value="<?= e($genderVal) ?>" <?= ($old['gender'] ?? 'other') === $genderVal ? 'selected' : '' ?>>
                            <?= e($genderLbl) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['gender'])): ?><p class="error"><?= e($errors['gender']) ?></p><?php endif; ?>
            </div>
        </div>
 
        <div class="form-row">
            <label for="note">Note</label>
            <div class="input-container">
                <textarea id="note" name="note" rows="4" placeholder="Nhập tiền sử bệnh lý, dị ứng thuốc..."><?= e($old['note'] ?? '') ?></textarea>
            </div>
        </div>
 
        <div class="form-actions-horizontal">
            <button class="btn primary" type="submit">Register Patient</button>
            <a class="btn btn-cancel" href="/patients">Back to Patients</a>
        </div>
    </form>
 
    <div class="requirements-card">
        <?php if (!empty($isDuplicate)): ?>
            <h3 style="text-align: center; margin-bottom: 24px;">Duplicate handling flow</h3>
            <div class="flowchart-container">
                <div class="flowchart-step">1. PHP validate input</div>
                <div class="flowchart-arrow">I</div>
                <div class="flowchart-step">2. Repository INSERT</div>
                <div class="flowchart-arrow">I</div>
                <div class="flowchart-step">3. MySQL unique_patient_email</div>
                <div class="flowchart-arrow">I</div>
                <div class="flowchart-step">4. Catch DuplicateRecordException</div>
                <div class="flowchart-arrow">I</div>
                <div class="flowchart-step">5. Show friendly error</div>
            </div>
        <?php else: ?>
            <h3>Form requirements</h3>
            <ul class="requirements-list">
                <li class="checked">Validate required fields</li>
                <li class="checked">Email format check</li>
                <li class="checked">Phone length/pattern</li>
                <li class="checked">Prepared statement INSERT</li>
                <li class="checked">Catch duplicate email</li>
                <li class="checked">PRG after success</li>
                <li class="checked">Keep old data when error</li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Register Patient';
require __DIR__ . '/../layout.php';
