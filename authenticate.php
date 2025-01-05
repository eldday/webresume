<?php
session_start();
// Database connection details
$host = "$IP";
$dbname = "resume";
$username = "pday";
$password = "quality";

header('Content-Type: application/json');

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

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
