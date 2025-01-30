
<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

// Connect to MySQL without selecting a database
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the list of databases
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
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
                echo "Database configuration updated successfully.";
            } else {
                echo "Failed to update database configuration.";
            }
        } else {
            echo "Error: Configuration file directory is not writable. Check permissions.";
        }
    } else {
        echo "All fields are required.";
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
            
            <button type="submit">Update Config</button>
        </form>
    </div>
</body>
</html>
