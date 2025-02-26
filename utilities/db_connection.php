<?php
$dbname = '';
$username = '';
$password = '';

try {
    $pdo = new PDO('mysql:dbname=' . $dbname . ';charset=utf8', $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
