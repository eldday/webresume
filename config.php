<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

$message = ""; // Store messages to display

// Connect to MySQL without selecting a database
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "pday", "quality");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the list of databases
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $message = "Database connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbname = $_POST['dbname'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($dbname && $username && $password) {
        $configContent = "<?php\n"
            . "\$dbname = '" . addslashes($dbname) . "';\n"
            . "\$username = '" . addslashes($username) . "';\n"
            . "\$password = '" . addslashes($password) . "';\n"
            . "\n"
            . "try {\n"
            . "    \$pdo = new PDO('mysql:dbname=' . \$dbname . ';charset=utf8', \$username, \$password);\n"
            . "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n"
            . "} catch (PDOException \$e) {\n"
            . "    die('Database connection failed: ' . \$e->getMessage());\n"
            . "}\n"
            . "?>";

        // Check if file is writable before attempting to write
        $configPath = 'utilities/db_connection.php';
        if (is_writable(dirname($configPath))) {
            if (file_put_contents($configPath, $configContent) !== false) {
                $message = "<p style='color: green;'>Database configuration updated successfully.</p>";
            } else {
                $message = "<p style='color: red;'>Failed to update database configuration.</p>";
            }
        } else {
            $message = "<p style='color: red;'>Error: Configuration file directory is not writable. Check permissions.</p>";
        }
    } else {
        $message = "<p style='color: red;'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration</title>
    <link rel="stylesheet" href="css/modal-style.css">
</head>
<body>
    <div class="container">
<link rel="stylesheet" href="css/modal-style.css">
        <h2>Update Database Configuration</h2>
        <p>Warning you are configuring the connection to the database if you accidentally  specify the wrong credentials or  database  the site will not run until you update db_connection.php from console!</p>
        <?php echo $message; ?>
        <form method="post">
            <label>Database Name:</label>
            <select name="dbname" required>
                <?php foreach ($databases as $db): ?>
                    <option value="<?php echo htmlspecialchars($db); ?>"><?php echo htmlspecialchars($db); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <br><button align="right" type="submit">Update Config</button>
        </form>
    </div>
</body>
</html>
