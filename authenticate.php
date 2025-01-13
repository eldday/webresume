<?php
session_start();

// Check if the user is already authenticated
if (isset($_SESSION['accessLevel'])) {
    echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
    exit();
}

// Define session timeout duration (e.g., 30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check session timeout
if (isset($_SESSION['lastActivity'])) {
    $elapsedTime = time() - $_SESSION['lastActivity'];
    if ($elapsedTime > $timeoutDuration) {
        session_unset();
        session_destroy();
        echo json_encode(["success" => false, "message" => "Session expired. Please log in again."]);
        exit();
    }
}

// Update the last activity timestamp
$_SESSION['lastActivity'] = time();

require_once 'db_connection.php';
header('Content-Type: application/json');

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_POST['login_id'] ?? '';
    $login_pword = $_POST['login_pword'] ?? '';

    // Validate input
    if (empty($login_id) || empty($login_pword)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit();
    }

    try {
        // Prepare SQL query to fetch user details and access level
        $stmt = $pdo->prepare("
            SELECT ua.login_pword, al.access_level
            FROM `user-accounts` ua
            JOIN `access_levels` al ON ua.access_id = al.access_id
            WHERE ua.login_id = :login_id
        ");
        $stmt->bindParam(':login_id', $login_id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password and set session variables
        if ($user && password_verify($login_pword, $user['login_pword'])) {
            // Login successful: Set session variables
            $_SESSION['accessLevel'] = $user['access_level'];
            $_SESSION['login_id'] = $login_id;
            $_SESSION['lastActivity'] = time();

            echo json_encode([
                "success" => true,
                "accessLevel" => $user['access_level']
            ]);
        } else {
            // Invalid username or password
            echo json_encode([
                "success" => false,
                "message" => "Invalid username or password."
            ]);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
    exit();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit();
}
