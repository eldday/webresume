<?php
session_start();
if (isset($_SESSION['accessLevel'])) {
echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
}
if ($result['success']) {
    $_SESSION['accessLevel'] = $result['accessLevel']; // Set access level
    $_SESSION['login_id'] = $result['login_id']; // Optional: Store user ID
}
// Define session timeout duration (e.g., 30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check if "lastActivity" is set in the session
if (isset($_SESSION['lastActivity'])) {
    // Calculate the session's lifetime
    $elapsedTime = time() - $_SESSION['lastActivity'];

    // If the session has expired
    if ($elapsedTime > $timeoutDuration) {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        exit();
    }
}

require_once 'db_connection.php';
header('Content-Type: application/json');


// Check if login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_POST['login_id'] ?? '';
    $login_pword = $_POST['login_pword'] ?? '';

    // Validate input
    if (empty($login_id) || empty($login_pword)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }

    // Prepare SQL query to fetch user details and access level
    $stmt = $pdo->prepare(
        "SELECT ua.login_pword, al.access_level 
         FROM `user-accounts` ua 
         JOIN `access_levels` al ON ua.access_id = al.access_id 
         WHERE ua.login_id = :login_id"
    );
    $stmt->bindParam(':login_id', $login_id);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($login_pword, $user['login_pword'])) {
        echo json_encode([
            "success" => true,
            "accessLevel" => $user['access_level']
	]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    }
}
?>
