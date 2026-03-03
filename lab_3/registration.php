<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 3 - Registration (MySQL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="list.php">Lab 3</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="registration.php">New Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="list.php">All Registrations</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-3">Registration Form</h1>

    <form method="post" action="submit.php" class="row g-3">
        <div class="col-md-6">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" required>
        </div>

        <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" rows="3" class="form-control"></textarea>
        </div>

        <div class="col-md-4">
            <label for="country" class="form-label">Country</label>
            <select id="country" name="country" class="form-select">
                <option value="">Select Country</option>
                <option value="Egypt">Egypt</option>
                <option value="USA">USA</option>
                <option value="UK">UK</option>
                <option value="Germany">Germany</option>
                <option value="France">France</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label d-block">Gender</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="male" name="gender" value="Male">
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="female" name="gender" value="Female">
                <label class="form-check-label" for="female">Female</label>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label d-block">Skills</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skill_php" name="skills[]" value="PHP">
                <label class="form-check-label" for="skill_php">PHP</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skill_mysql" name="skills[]" value="MySQL" checked>
                <label class="form-check-label" for="skill_mysql">MySQL</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skill_j2se" name="skills[]" value="J2SE" checked>
                <label class="form-check-label" for="skill_j2se">J2SE</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skill_postgresql" name="skills[]" value="PostgreSQL">
                <label class="form-check-label" for="skill_postgresql">PostgreSQL</label>
            </div>
        </div>

        <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="department" class="form-label">Department</label>
            <input type="text" id="department" name="department" value="OpenSource" class="form-control">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary ms-2">Reset</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>