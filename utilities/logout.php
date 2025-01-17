<?php
session_start();
session_unset();
session_destroy();

// Debug to ensure the session is cleared
error_log("Session destroyed successfully.");

// Respond with a success message
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
?>
