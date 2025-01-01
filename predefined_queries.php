<?php
// Base URL of the API file
$apiUrl = "http://172.18.3.150/api.php";

// Predefined queries
$queries = [
    "timeline" => 'SELECT * FROM Job_history companies join  Job_history on companies.company_name = Job_history.company_name;',
    "all_jobs" => 'SELECT * FROM Job_history;',
    "company" => 'SELECT * FROM companies;',
    "Companies" => 'SELECT * FROM resume.companies;',
];

// Get the endpoint parameter
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;

// Check if the endpoint exists in the predefined queries
if (!$endpoint || !array_key_exists($endpoint, $queries)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing endpoint parameter."]);
    exit;
}

// Build the API URL with the query
$query = urlencode($queries[$endpoint]);
$apiRequestUrl = "$apiUrl?query=$query";

// Fetch the API response
$response = file_get_contents($apiRequestUrl);

if ($response === false) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch data from the API."]);
    exit;
}

// Return the API response
header("Content-Type: application/json");
echo $response;
?>
