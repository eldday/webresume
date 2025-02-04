<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";

   exit();
}


// Include database connection
require_once 'utilities/db_connection.php';

$message = '';
$users = [];

// Fetch existing users to display in the sidebar
try {
    $stmt = $pdo->query('SELECT id, username, access_level FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching users: " . $e->getMessage();
}

// Initialize selected user variable
$selectedUser = null;

// If a user ID is passed via GET, fetch their details
if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $selectedUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = "Error fetching user details: " . $e->getMessage();
    }
}

// Handle form submission for creating/updating a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['user_id'])) {
    $id = $_POST['user_id'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? '';
    $access_level = $_POST['access_level'] ?? null;

    if (!$username || !$access_level) {
        $message = "Username and Access Level are required.";
    } else {
        try {
            if ($id) {
                // Update existing user
                if (!empty($password)) {
                    // Update password only if provided
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('UPDATE users SET username = :username, password = :password, access_level = :access_level WHERE id = :id');
                    $stmt->execute([
                        ':username' => $username,
                        ':password' => $hashedPassword,
                        ':access_level' => $access_level,
                        ':id' => $id
                    ]);
                } else {
                    // Update without changing password
                    $stmt = $pdo->prepare('UPDATE users SET username = :username, access_level = :access_level WHERE id = :id');
                    $stmt->execute([
                        ':username' => $username,
                        ':access_level' => $access_level,
                        ':id' => $id
                    ]);
                }
                $message = "User updated successfully.";
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, password, access_level) VALUES (:username, :password, :access_level)');
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':access_level' => $access_level
                ]);
                $message = "User created successfully.";
            }

            // Refresh the user list after update or creation
            $stmt = $pdo->query('SELECT id, username, access_level FROM users');
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
        }
        .list {
            width: 20%;
            border-right: 1px solid #ccc;
            padding: 10px;
        }
        .list ul {
            list-style: none;
            padding: 0;
        }
        .list ul li {
            margin: 5px 0;
        }
        .list ul li a {
            text-decoration: none;
            color: blue;
        }
        .details {
            width: 80%;
            padding: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>
<body>
<link rel="stylesheet" href="css/modal-style.css">
    <div class="list">
        <h2>Existing Users</h2>
        <ul class="user-list">
<link rel="stylesheet" href="css/modal-style.css">
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="?user_id=<?php echo $user['id']; ?>">
                        <?php echo htmlspecialchars($user['username']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="details">
	<link rel="stylesheet" href="css/modal-style.css">
         <hr style="height:3px;border-width:0;color:white;background-color:blue">
	<h2>User Management</h2>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="user_id" value="<?php echo $selectedUser['id'] ?? ''; ?>">

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($selectedUser['username'] ?? ''); ?>" required><br><br>

            <label for="password">Password (leave blank to keep existing):</label><br>
            <input type="password" id="password" name="password"><br><br>

            <label for="access_level">Access Level:</label><br>
            <select id="access_level" name="access_level" required>
                <option value="admin" <?php echo (isset($selectedUser['access_level']) && $selectedUser['access_level'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo (isset($selectedUser['access_level']) && $selectedUser['access_level'] === 'user') ? 'selected' : ''; ?>>User</option>
                <option value="guest" <?php echo (isset($selectedUser['access_level']) && $selectedUser['access_level'] === 'guest') ? 'selected' : ''; ?>>Guest</option>
            </select><br><br>

            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
