<?php
// Database connection details
$host = "$IP";
$dbname = "";
$username = "";
$password = "";

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all user accounts
$stmt = $pdo->query("SELECT user_id, login_pword FROM `user-accounts`");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $userId = $user['user_id'];
    $plainPassword = $user['login_pword']; // Assuming currently stored as plain text

    // Hash the password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update the user record with the hashed password
    $updateStmt = $pdo->prepare("UPDATE `user-accounts` SET login_pword = :hashedPassword WHERE user_id = :userId");
    $updateStmt->execute([':hashedPassword' => $hashedPassword, ':userId' => $userId]);
}

echo "Passwords have been securely hashed.";
?>
