<?php
require_once __DIR__ . '/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 4 - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lab4-primary: #2563eb; --lab4-bg: #f8fafc; }
        body { font-family: 'DM Sans', sans-serif; background: var(--lab4-bg); min-height: 100vh; }
        .lab4-nav { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lab4-nav .navbar-brand { font-weight: 700; color: #fff !important; }
        .lab4-nav .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; }
        .hero { padding: 3rem 0; }
        .hero h1 { font-weight: 700; color: #1e293b; }
        .card-quick { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); transition: transform 0.2s, box-shadow 0.2s; }
        .card-quick:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
        .card-quick .card-body { padding: 1.5rem; }
    </style>
</head>
<body>
<?php include __DIR__ . '/_nav_private.php'; ?>

<div class="container hero">
    <h1 class="mb-2">Welcome, <?php echo htmlspecialchars(getLoggedInUsername()); ?></h1>
    <p class="text-muted mb-4">Choose an action below.</p>
    <div class="row g-3">
        <div class="col-md-6">
            <a href="registration.php" class="text-decoration-none">
                <div class="card card-quick h-100">
                    <div class="card-body">
                        <h5 class="card-title">New Registration</h5>
                        <p class="card-text text-muted small mb-0">Add a new user registration.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="list.php" class="text-decoration-none">
                <div class="card card-quick h-100">
                    <div class="card-body">
                        <h5 class="card-title">All Registrations</h5>
                        <p class="card-text text-muted small mb-0">View and manage all registrations.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
