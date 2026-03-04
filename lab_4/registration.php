<?php
require_once __DIR__ . '/auth.php';
$pageTitle = 'Register';
$form_errors = [];
$form_old = [];
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['form_errors'])) {
    $form_errors = $_SESSION['form_errors'];
    unset($_SESSION['form_errors']);
}
if (!empty($_SESSION['form_old'])) {
    $form_old = $_SESSION['form_old'];
    unset($_SESSION['form_old']);
}
$countries = ['Egypt', 'USA', 'UK', 'Germany', 'France'];
$all_skills = ['PHP', 'MySQL', 'J2SE', 'PostgreSQL'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lab4-primary: #2563eb;
            --lab4-primary-dark: #1d4ed8;
            --lab4-bg: #f8fafc;
            --lab4-card: #ffffff;
            --lab4-border: #e2e8f0;
            --lab4-error: #dc2626;
            --lab4-success: #16a34a;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand { font-weight: 700; color: #fff !important; }
        .lab4-nav .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; }
        .lab4-nav .nav-link:hover { color: #fff !important; }
        .lab4-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .lab4-card .card-header { background: transparent; border-bottom: 1px solid var(--lab4-border); font-weight: 600; padding: 1rem 1.5rem; border-radius: 16px 16px 0 0; }
        .form-label { font-weight: 500; color: #334155; }
        .form-control, .form-select { border-radius: 10px; border: 1px solid var(--lab4-border); }
        .form-control:focus, .form-select:focus { border-color: var(--lab4-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .invalid-feedback { color: var(--lab4-error); font-size: 0.875rem; }
        .form-control.is-invalid { border-color: var(--lab4-error); }
        .btn-lab4-primary { background: var(--lab4-primary); color: #fff; border: none; border-radius: 10px; font-weight: 600; padding: 0.6rem 1.5rem; }
        .btn-lab4-primary:hover { background: var(--lab4-primary-dark); color: #fff; }
        .profile-upload-wrap { border: 2px dashed var(--lab4-border); border-radius: 12px; padding: 1.5rem; text-align: center; background: #f8fafc; }
        .profile-upload-wrap.has-file { border-color: var(--lab4-primary); background: rgba(37, 99, 235, 0.05); }
        .skill-group .form-check { margin-bottom: 0.35rem; }
        .gender-group .form-check-inline { margin-right: 1rem; }
        .gender-group.is-invalid .form-check-input { border-color: var(--lab4-error); }
        .skill-group.is-invalid .form-check-input { border-color: var(--lab4-error); }
    </style>
</head>
<body>
<?php include (isset($_SESSION['user_id']) && $_SESSION['user_id']) ? __DIR__ . '/_nav_private.php' : __DIR__ . '/_nav_public.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card lab4-card">
                <div class="card-header">Create your account</div>
                <div class="card-body p-4">
                    <form id="registrationForm" method="post" action="submit.php" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control <?php echo isset($form_errors['first_name']) ? 'is-invalid' : ''; ?>"
                                   value="<?php echo htmlspecialchars($form_old['first_name'] ?? ''); ?>" required>
                            <div class="invalid-feedback" id="err_first_name"><?php echo isset($form_errors['first_name']) ? htmlspecialchars($form_errors['first_name']) : ''; ?></div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control <?php echo isset($form_errors['last_name']) ? 'is-invalid' : ''; ?>"
                                   value="<?php echo htmlspecialchars($form_old['last_name'] ?? ''); ?>" required>
                            <div class="invalid-feedback" id="err_last_name"><?php echo isset($form_errors['last_name']) ? htmlspecialchars($form_errors['last_name']) : ''; ?></div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea id="address" name="address" rows="3" class="form-control <?php echo isset($form_errors['address']) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($form_old['address'] ?? ''); ?></textarea>
                            <div class="invalid-feedback" id="err_address"><?php echo isset($form_errors['address']) ? htmlspecialchars($form_errors['address']) : ''; ?></div>
                        </div>

                        <div class="col-md-6">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <select id="country" name="country" class="form-select <?php echo isset($form_errors['country']) ? 'is-invalid' : ''; ?>" required>
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($form_old['country'] ?? '') === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="err_country"><?php echo isset($form_errors['country']) ? htmlspecialchars($form_errors['country']) : ''; ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
                            <div class="gender-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="male" name="gender" value="Male" <?php echo ($form_old['gender'] ?? '') === 'Male' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="female" name="gender" value="Female" <?php echo ($form_old['gender'] ?? '') === 'Female' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block" id="err_gender"><?php echo isset($form_errors['gender']) ? htmlspecialchars($form_errors['gender']) : ''; ?></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block">Skills <span class="text-danger">*</span></label>
                            <div class="skill-group">
                                <?php foreach ($all_skills as $s): ?>
                                    <?php $checked = in_array($s, $form_old['skills'] ?? [], true) ? 'checked' : ''; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="skill_<?php echo strtolower($s); ?>" name="skills[]" value="<?php echo htmlspecialchars($s); ?>" <?php echo $checked; ?>>
                                        <label class="form-check-label" for="skill_<?php echo strtolower($s); ?>"><?php echo htmlspecialchars($s); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="invalid-feedback d-block" id="err_skills"><?php echo isset($form_errors['skills']) ? htmlspecialchars($form_errors['skills']) : ''; ?></div>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" class="form-control <?php echo isset($form_errors['username']) ? 'is-invalid' : ''; ?>"
                                   value="<?php echo htmlspecialchars($form_old['username'] ?? ''); ?>" required>
                            <div class="invalid-feedback" id="err_username"><?php echo isset($form_errors['username']) ? htmlspecialchars($form_errors['username']) : ''; ?></div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control <?php echo isset($form_errors['password']) ? 'is-invalid' : ''; ?>" required>
                            <small class="text-muted">Exactly 8 characters; only lowercase letters, numbers, and underscore.</small>
                            <div class="invalid-feedback" id="err_password"><?php echo isset($form_errors['password']) ? htmlspecialchars($form_errors['password']) : ''; ?></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Profile picture (optional)</label>
                            <div class="profile-upload-wrap" id="profileWrap">
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                <small class="text-muted">JPG or PNG only. Max 2 MB.</small>
                            </div>
                            <div class="invalid-feedback d-block" id="err_profile_picture"><?php echo isset($form_errors['profile_picture']) ? htmlspecialchars($form_errors['profile_picture']) : ''; ?></div>
                        </div>

                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-lab4-primary">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary ms-2">Reset</button>
                            <a href="login.php" class="btn btn-link">Already have an account? Log in</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
(function() {
    var form = document.getElementById('registrationForm');
    var MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    function noNumbers(str) {
        return !/[0-9]/.test(str);
    }
    function onlyLettersSpaces(str) {
        return /^[\p{L}\s\-\.\']+$/u.test(str);
    }
    function passwordValid(p) {
        if (p.length !== 8) return 'Password must be exactly 8 characters.';
        if (/[A-Z]/.test(p)) return 'Password cannot contain capital letters.';
        if (/[^a-z0-9_]/.test(p)) return 'Password may only contain lowercase letters, numbers, and underscore.';
        return null;
    }

    function showError(id, msg) {
        var el = document.getElementById(id);
        var err = document.getElementById('err_' + id);
        if (msg) {
            if (el) el.classList.add('is-invalid');
            if (err) { err.textContent = msg; err.style.display = 'block'; }
        } else {
            if (el) el.classList.remove('is-invalid');
            if (err) { err.textContent = ''; err.style.display = 'none'; }
        }
    }
    function setBlockError(errId, msg) {
        var wrap = document.querySelector('.gender-group');
        var skillWrap = document.querySelector('.skill-group');
        var err = document.getElementById(errId);
        if (errId === 'err_gender' && wrap) { wrap.classList.toggle('is-invalid', !!msg); }
        if (errId === 'err_skills' && skillWrap) { skillWrap.classList.toggle('is-invalid', !!msg); }
        if (err) { err.textContent = msg || ''; err.style.display = msg ? 'block' : 'none'; }
    }

    function validate() {
        var ok = true;
        var first = document.getElementById('first_name').value.trim();
        var last = document.getElementById('last_name').value.trim();
        var address = document.getElementById('address').value.trim();
        var country = document.getElementById('country').value;
        var gender = document.querySelector('input[name="gender"]:checked');
        var skills = document.querySelectorAll('input[name="skills[]"]:checked');
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value;
        var file = document.getElementById('profile_picture').files[0];

        showError('first_name', null);
        showError('last_name', null);
        showError('address', null);
        showError('country', null);
        setBlockError('err_gender', null);
        setBlockError('err_skills', null);
        showError('username', null);
        showError('password', null);
        showError('profile_picture', null);

        if (!first) { showError('first_name', 'First Name is required.'); ok = false; }
        else if (!noNumbers(first) || !onlyLettersSpaces(first)) { showError('first_name', 'First Name cannot contain numbers.'); ok = false; }

        if (!last) { showError('last_name', 'Last Name is required.'); ok = false; }
        else if (!noNumbers(last) || !onlyLettersSpaces(last)) { showError('last_name', 'Last Name cannot contain numbers.'); ok = false; }

        if (!address) { showError('address', 'Address is required.'); ok = false; }
        if (!country) { showError('country', 'Country is required.'); ok = false; }
        if (!gender) { setBlockError('err_gender', 'Gender is required.'); ok = false; }
        if (skills.length === 0) { setBlockError('err_skills', 'At least one Skill must be selected.'); ok = false; }
        if (!username) { showError('username', 'Username is required.'); ok = false; }
        if (!password) { showError('password', 'Password is required.'); ok = false; }
        else { var pwErr = passwordValid(password); if (pwErr) { showError('password', pwErr); ok = false; } }

        if (file) {
            var allowed = ['image/jpeg', 'image/jpg', 'image/png'];
            if (allowed.indexOf(file.type) === -1) { showError('profile_picture', 'Profile picture must be JPG or PNG only.'); ok = false; }
            else if (file.size > MAX_FILE_SIZE) { showError('profile_picture', 'Profile picture must be less than 2 MB.'); ok = false; }
        }

        return ok;
    }

    form.addEventListener('submit', function(e) {
        if (!validate()) e.preventDefault();
    });

    document.getElementById('profile_picture').addEventListener('change', function() {
        document.getElementById('profileWrap').classList.toggle('has-file', this.files.length > 0);
    });
})();
</script>
</body>
</html>
