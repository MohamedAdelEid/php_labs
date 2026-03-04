<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$result = $mysqli->query("SELECT * FROM lab4_registrations ORDER BY id DESC");
$rows = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - All Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lab4-primary: #2563eb; --lab4-bg: #f8fafc; }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand, .lab4-nav .nav-link { color: rgba(255,255,255,0.95) !important; font-weight: 500; }
        .table th { font-weight: 600; color: #334155; }
        .table-responsive { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .avatar-sm { width: 36px; height: 36px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
<?php include __DIR__ . '/_nav_private.php'; ?>

<div class="container py-4">
    <h1 class="h3 mb-4">All Registrations</h1>
    <?php if (empty($rows)): ?>
        <div class="alert alert-info">No registrations yet. <a href="registration.php" class="alert-link">Register</a> to add the first record.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Username</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo (int)$row['id']; ?></td>
                        <td>
                            <?php if (!empty($row['profile_picture']) && file_exists(__DIR__ . '/' . $row['profile_picture'])): ?>
                                <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="" class="avatar-sm">
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['country'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <a href="delete.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
