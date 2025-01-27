<?php
// Start the session
session_start();

// Include database connection file
require_once 'utilities/db_connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare a statement to fetch user details
    $stmt = $pdo->prepare('SELECT id, username, password, access_level FROM users WHERE username = :username LIMIT 1');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the entered password with the stored hashed password
        if (password_verify($password, $username['password'])) {
            // Password is correct, set session variables
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['accessLevel'] = $user['access_level'];
            $_SESSION['lastActivity'] = time(); // Set the session expiration timer

            // Respond with a success message
            echo json_encode([
                'success' => true,
                'accessLevel' => $user['access_level']
            ]);
        } else {
            // Invalid password
            echo json_encode([
                'success' => false,
                'message' => 'Invalid password.'
            ]);
        }
    } else {
        // User not found
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username.'
        ]);
    }
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>
