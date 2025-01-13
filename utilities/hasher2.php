<?php
// Database connection details
$host = "$IP";
$dbname = "resume";
$username = "pday";
$password = "quality";

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all user accounts
$stmt = $pdo->query("SELECT id, password FROM `users`");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $userId = $user['id'];
    $plainPassword = $user['password']; // Assuming currently stored as plain text

    // Hash the password
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update the user record with the hashed password
    $updateStmt = $pdo->prepare("UPDATE `users` SET password = :hashedPassword WHERE id = :userId");
    $updateStmt->execute([':hashedPassword' => $hashedPassword, ':userId' => $userId]);
}

echo "Passwords have been securely hashed.";
?>
