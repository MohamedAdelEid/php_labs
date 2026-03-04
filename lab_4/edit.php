<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/validate.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
}

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$form_errors = [];
$form_old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $address    = isset($_POST['address']) ? trim($_POST['address']) : '';
    $country    = isset($_POST['country']) ? trim($_POST['country']) : '';
    $gender     = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $skillsArr  = isset($_POST['skills']) && is_array($_POST['skills']) ? $_POST['skills'] : [];
    $skills     = implode(', ', array_map('trim', $skillsArr));
    $username   = isset($_POST['username']) ? trim($_POST['username']) : '';

    if ($e = validateFirstName($first_name)) $form_errors['first_name'] = $e;
    if ($e = validateLastName($last_name)) $form_errors['last_name'] = $e;
    if ($e = validateAddress($address)) $form_errors['address'] = $e;
    if ($e = validateCountry($country)) $form_errors['country'] = $e;
    if ($e = validateGender($gender)) $form_errors['gender'] = $e;
    if ($e = validateSkills($skillsArr)) $form_errors['skills'] = $e;
    if ($e = validateUsername($username)) $form_errors['username'] = $e;

    if (!isset($form_errors['username'])) {
        $stmt = $mysqli->prepare("SELECT id FROM lab4_registrations WHERE username = ? AND id != ?");
        $stmt->bind_param('si', $username, $id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $form_errors['username'] = 'This username is already taken.';
        }
        $stmt->close();
    }

    if (!empty($_FILES['profile_picture']['name']) && ($e = validateProfilePicture($_FILES['profile_picture']))) {
        $form_errors['profile_picture'] = $e;
    }

    if (empty($form_errors)) {
        $profile_path = null;
        $current = null;
        $stmt = $mysqli->prepare("SELECT profile_picture FROM lab4_registrations WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($res) $current = $res['profile_picture'];

        if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            if ($ext === 'jpeg') $ext = 'jpg';
            $filename = 'profile_' . uniqid() . '_' . preg_replace('/[^a-z0-9_]/', '', $username) . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
                $profile_path = 'uploads/' . $filename;
                if ($current && file_exists(__DIR__ . '/' . $current)) {
                    @unlink(__DIR__ . '/' . $current);
                }
            }
        }

        if ($profile_path !== null) {
            $sql = "UPDATE lab4_registrations SET first_name=?, last_name=?, address=?, country=?, gender=?, skills=?, username=?, profile_picture=? WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ssssssssi', $first_name, $last_name, $address, $country, $gender, $skills, $username, $profile_path, $id);
        } else {
            $sql = "UPDATE lab4_registrations SET first_name=?, last_name=?, address=?, country=?, gender=?, skills=?, username=? WHERE id=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssssssi', $first_name, $last_name, $address, $country, $gender, $skills, $username, $id);
        }
        $stmt->execute();
        $stmt->close();
        header('Location: list.php');
        exit;
    }

    $form_old = [
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'address'    => $address,
        'country'    => $country,
        'gender'     => $gender,
        'skills'     => $skillsArr,
        'username'   => $username,
    ];
}

$stmt = $mysqli->prepare("SELECT * FROM lab4_registrations WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    header('Location: list.php');
    exit;
}

if (empty($form_old)) {
    $form_old = [
        'first_name' => $row['first_name'],
        'last_name'  => $row['last_name'],
        'address'    => $row['address'],
        'country'    => $row['country'],
        'gender'     => $row['gender'],
        'skills'     => array_map('trim', $row['skills'] ? explode(',', $row['skills']) : []),
        'username'   => $row['username'],
    ];
}

$countries = ['Egypt', 'USA', 'UK', 'Germany', 'France'];
$all_skills = ['PHP', 'MySQL', 'J2SE', 'PostgreSQL'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - Edit Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lab4-primary: #2563eb; --lab4-primary-dark: #1d4ed8; --lab4-bg: #f8fafc; --lab4-border: #e2e8f0; --lab4-error: #dc2626; }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand, .lab4-nav .nav-link { color: rgba(255,255,255,0.95) !important; font-weight: 500; }
        .lab4-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .form-control, .form-select { border-radius: 10px; border: 1px solid var(--lab4-border); }
        .form-control:focus, .form-select:focus { border-color: var(--lab4-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .btn-lab4-primary { background: var(--lab4-primary); color: #fff; border: none; border-radius: 10px; font-weight: 600; }
        .btn-lab4-primary:hover { color: #fff; background: var(--lab4-primary-dark); }
        .profile-upload-wrap { border: 2px dashed var(--lab4-border); border-radius: 12px; padding: 1rem; background: #f8fafc; }
        .gender-group.is-invalid .form-check-input, .skill-group.is-invalid .form-check-input { border-color: var(--lab4-error); }
    </style>
</head>
<body>
<?php include __DIR__ . '/_nav_private.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Registration</h1>
        <div>
            <a href="list.php" class="btn btn-outline-secondary btn-sm">Back to list</a>
            <a href="view.php?id=<?php echo $id; ?>" class="btn btn-outline-primary btn-sm ms-1">View</a>
        </div>
    </div>

    <div class="card lab4-card">
        <div class="card-body p-4">
            <form id="editForm" method="post" action="edit.php" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

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

                <div class="col-md-4">
                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                    <select id="country" name="country" class="form-select <?php echo isset($form_errors['country']) ? 'is-invalid' : ''; ?>" required>
                        <option value="">Select Country</option>
                        <?php foreach ($countries as $c): ?>
                            <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($form_old['country'] ?? '') === $c ? 'selected' : ''; ?>><?php echo htmlspecialchars($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="err_country"><?php echo isset($form_errors['country']) ? htmlspecialchars($form_errors['country']) : ''; ?></div>
                </div>

                <div class="col-md-4">
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

                <div class="col-md-4">
                    <label class="form-label d-block">Skills <span class="text-danger">*</span></label>
                    <div class="skill-group">
                        <?php foreach ($all_skills as $s): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skill_<?php echo strtolower($s); ?>" name="skills[]" value="<?php echo htmlspecialchars($s); ?>"
                                    <?php echo in_array($s, $form_old['skills'] ?? [], true) ? 'checked' : ''; ?>>
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

                <div class="col-12">
                    <label class="form-label">Profile picture</label>
                    <?php if (!empty($row['profile_picture']) && file_exists(__DIR__ . '/' . $row['profile_picture'])): ?>
                        <p class="small text-muted mb-1">Current: <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:50%;"> Leave empty to keep.</p>
                    <?php endif; ?>
                    <div class="profile-upload-wrap">
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <small class="text-muted">JPG or PNG only. Max 2 MB.</small>
                    </div>
                    <div class="invalid-feedback d-block" id="err_profile_picture"><?php echo isset($form_errors['profile_picture']) ? htmlspecialchars($form_errors['profile_picture']) : ''; ?></div>
                </div>

                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-lab4-primary">Update</button>
                    <a href="list.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
(function() {
    document.getElementById('editForm').addEventListener('submit', function(e) {
        var first = document.getElementById('first_name').value.trim();
        var last = document.getElementById('last_name').value.trim();
        var address = document.getElementById('address').value.trim();
        var country = document.getElementById('country').value;
        var gender = document.querySelector('input[name="gender"]:checked');
        var skills = document.querySelectorAll('input[name="skills[]"]:checked');
        var username = document.getElementById('username').value.trim();
        function noNumbers(s) { return !/[0-9]/.test(s); }
        function onlyLetters(s) { return /^[\p{L}\s\-\.\']+$/u.test(s); }
        function setErr(id, msg) {
            var el = document.getElementById(id), err = document.getElementById('err_' + id);
            if (el) el.classList.toggle('is-invalid', !!msg);
            if (err) { err.textContent = msg || ''; err.style.display = msg ? 'block' : 'none'; }
        }
        function setBlockErr(errId, msg) {
            var err = document.getElementById(errId);
            if (errId === 'err_gender') document.querySelector('.gender-group').classList.toggle('is-invalid', !!msg);
            if (errId === 'err_skills') document.querySelector('.skill-group').classList.toggle('is-invalid', !!msg);
            if (err) { err.textContent = msg || ''; err.style.display = msg ? 'block' : 'none'; }
        }
        setErr('first_name', null); setErr('last_name', null); setErr('address', null); setErr('country', null);
        setBlockErr('err_gender', null); setBlockErr('err_skills', null); setErr('username', null); setErr('profile_picture', null);
        var ok = true;
        if (!first) { setErr('first_name', 'First Name is required.'); ok = false; }
        else if (!noNumbers(first) || !onlyLetters(first)) { setErr('first_name', 'First Name cannot contain numbers.'); ok = false; }
        if (!last) { setErr('last_name', 'Last Name is required.'); ok = false; }
        else if (!noNumbers(last) || !onlyLetters(last)) { setErr('last_name', 'Last Name cannot contain numbers.'); ok = false; }
        if (!address) { setErr('address', 'Address is required.'); ok = false; }
        if (!country) { setErr('country', 'Country is required.'); ok = false; }
        if (!gender) { setBlockErr('err_gender', 'Gender is required.'); ok = false; }
        if (skills.length === 0) { setBlockErr('err_skills', 'At least one Skill must be selected.'); ok = false; }
        if (!username) { setErr('username', 'Username is required.'); ok = false; }
        var file = document.getElementById('profile_picture').files[0];
        if (file) {
            if (['image/jpeg','image/jpg','image/png'].indexOf(file.type) === -1) { setErr('profile_picture', 'JPG or PNG only.'); ok = false; }
            else if (file.size > 2*1024*1024) { setErr('profile_picture', 'Max 2 MB.'); ok = false; }
        }
        if (!ok) e.preventDefault();
    });
})();
</script>
</body>
</html>
