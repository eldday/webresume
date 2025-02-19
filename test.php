<?php
session_start();

// Check if the user is logged in already
if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] != 'view') {
    // Redirect to admin interface
    header("Location: admindex.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate a login process
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Dummy validation for demonstration purposes
    if ($username == 'admin' && $password == 'password') {
        $_SESSION['accessLevel'] = 'admin'; // Set user session
        header("Location: admindex.php"); // Redirect to the admin interface
        exit();
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>

        <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="./assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
