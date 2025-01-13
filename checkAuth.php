<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] !== null) {
    echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
} else {
    echo json_encode(['success' => false, 'accessLevel' => null]);
}
