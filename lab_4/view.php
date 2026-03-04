<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: list.php');
    exit;
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

$skills_text = $row['skills'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - View Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lab4-primary: #2563eb; --lab4-bg: #f8fafc; }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand, .lab4-nav .nav-link { color: rgba(255,255,255,0.95) !important; font-weight: 500; }
        .profile-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .profile-avatar { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; }
        .profile-avatar-placeholder { width: 120px; height: 120px; border-radius: 50%; flex-shrink: 0; }
    </style>
</head>
<body>
<?php include __DIR__ . '/_nav_private.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">View Registration</h1>
        <div>
            <a href="list.php" class="btn btn-outline-secondary btn-sm">Back to list</a>
            <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm ms-1">Edit</a>
        </div>
    </div>

    <div class="card profile-card">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-4 mb-4">
                <?php if (!empty($row['profile_picture']) && file_exists(__DIR__ . '/' . $row['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile" class="profile-avatar">
                <?php else: ?>
                    <div class="profile-avatar profile-avatar-placeholder bg-light d-flex align-items-center justify-content-center text-muted small">No photo</div>
                <?php endif; ?>
                <div>
                    <h2 class="h5 mb-1"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h2>
                    <p class="text-muted mb-0">@<?php echo htmlspecialchars($row['username'] ?? ''); ?></p>
                </div>
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-3">First Name</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['first_name']); ?></dd>
                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['last_name']); ?></dd>
                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($row['address'] ?? '')); ?></dd>
                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['country'] ?? ''); ?></dd>
                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['gender'] ?? ''); ?></dd>
                <dt class="col-sm-3">Skills</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($skills_text); ?></dd>
                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['username'] ?? ''); ?></dd>
                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></dd>
            </dl>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
