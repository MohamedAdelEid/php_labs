<?php require_once __DIR__ . '/auth.php'; requireLogin(); $loggedUser = getLoggedInUsername(); ?>
<nav class="navbar navbar-expand-lg lab4-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Lab 4</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="registration.php">New Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="list.php">All Registrations</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item"><span class="nav-link text-white">Logged in as <strong><?php echo htmlspecialchars($loggedUser); ?></strong></span></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
