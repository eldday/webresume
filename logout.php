<?php
session_start();
session_unset();
session_destroy();
// Redirect to login page or notify via JSON
echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>
