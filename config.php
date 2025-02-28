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
    $pdo = new PDO("mysql:host=localhost;charset=utf8", "pday", "1970S3pt3mb3r25!");
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
                $message = "<p style='color: green; background-color: #afcca2;'>Database configuration updated successfully.</p>";
            } else {
                $message = "<p style='color: red; background-color: #dfa8bb;'>Failed to update database configuration.</p>";
            }
        } else {
            $message = "<p style='color: red; background-color: #dfa8bb;'>Error: Configuration file directory is not writable. Check permissions.</p>";
        }
    } else {
        $message = "<p style='color: red; background-color: #dfa8bb;'>All fields are required.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Configuration</title>
    <style>
        textarea {
          display: none; /* Hide textarea initially, as it's replaced by CKEditor */
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            border-right: 4px solid #ccc;
        }
        .details {
            width: 80%;
            padding: 20px;
            margint-right: 20px;
            margin-left: 20px;
        }
        .sidebar {
            width: 20%;
            padding: 20px;
            border-right: 4px solid #ccc;
            border-left: 4px solid #ccc;
        }
        .main {
            flex-grow: 1;
            padding: 5px;
        }
        .user-list {
            list-style: none;
            padding: 5;
        }
        .user-list li {
            margin-bottom: 5px;
            background-color: rgba(0, 0, 0, 0.059);
            border: solid #ffffff1f 0.75pt;
            border-width: 15 15 1px 0; /* Bottom border only */
            line-height: 1.2;
            margin-bottom: 5px;
            padding: 6pt 4pt;
            text-indent: 0;
            font-size: 16px;
            color: #333;
        }
        .list {
            width: 25%;
            border-right: 2px solid #ccc;
            border-left: 2px solid #ccc;
            padding: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
        }
        textarea {
            display: block; /* Ensure the textarea is visible initially */
           margin: 10px;
       }
    </style>
</head>
<body>
<link rel="stylesheet" href="css/modal-style.css">
    <div class="main">
	<h2><?php echo $message; ?></h2>
<hr style="height:3px;border-width:0;color:white;background-color:blue">
        <h2>Update Database Configuration</h2>
        <p>Warning you are configuring the connection to the database if you accidentally  specify the wrong credentials or  database  the site will not run until you update db_connection.php from console!</p>
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
