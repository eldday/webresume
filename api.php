<?php
// Configuration for the database connection
$host = "$IP";
$dbname = "resume";
$username = "pday";
$password = "quality";

// Set the content type to JSON
header("Content-Type: application/json");

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Get the query parameter from the URL
$query = isset($_GET['query']) ? $_GET['query'] : null;

// Ensure a query parameter is provided
if (!$query) {
    http_response_code(400);
    echo json_encode(["error" => "No query parameter provided."]);
    exit;
}

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($results);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
    exit;
}
?>
