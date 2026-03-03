<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Lab 2</title>
</head>
<body>
    <p><a href="list.php">View all registrations</a></p>
    <h1>Registration Form</h1>
    <form method="post" action="submit.php">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="4"></textarea><br><br>
        <label for="country">Country</label>
        <select id="country" name="country">
            <option value="">Select Country</option>
            <option value="Egypt">Egypt</option>
            <option value="USA">USA</option>
            <option value="UK">UK</option>
            <option value="Germany">Germany</option>
            <option value="France">France</option>
        </select><br><br>
        <label>Gender</label>
        <input type="radio" id="male" name="gender" value="Male">
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="Female">
        <label for="female">Female</label><br><br>
        <label>Skills</label><br>
        <input type="checkbox" id="skill_php" name="skills[]" value="PHP">
        <label for="skill_php">PHP</label>
        <input type="checkbox" id="skill_mysql" name="skills[]" value="MySQL" checked>
        <label for="skill_mysql">MySQL</label>
        <input type="checkbox" id="skill_j2se" name="skills[]" value="J2SE" checked>
        <label for="skill_j2se">J2SE</label>
        <input type="checkbox" id="skill_postgresql" name="skills[]" value="PostgreSQL">
        <label for="skill_postgresql">PostgreSQL</label><br><br>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="department">Department</label>
        <input type="text" id="department" name="department" value="OpenSource"><br><br>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
    </form>
</body>
</html>
