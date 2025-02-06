<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
require 'utilities/db_connection.php';
// Database connection
//$host = 'localhost';
//$dbname = 'resume';
//$username = 'pday';
//$password = 'quality';

//// Create a PDO instance
//try {
//    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//} catch (PDOException $e) {
//    die("Could not connect to the database: " . $e->getMessage());
//}

// Fetch profile
function getProfile($profile_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
    $stmt->execute(['profile_id' => $profile_id]);
    return $stmt->fetch();
}

// Assume profile_id is passed as a query parameter or hardcoded (e.g., profile_id = 1)
$profile_id = 1; // Update this with the actual profile_id you want to fetch
$profile = getProfile($profile_id);

// Fetch job history with company names (join Job_history with companies table)
$query = "
    SELECT j.job_title, c.company_name, j.start_date, j.end_date, j.job_description
    FROM Job_history j
    JOIN companies c ON j.company_id = c.company_id
    ORDER BY j.start_date DESC
";
$statement = $pdo->query($query);
$job_history = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch skills with category names (join skills with categories table)
$query = "
    SELECT s.Skill_name, c.category_name
    FROM skills s
    JOIN categories c ON s.Skill_category_id = c.category_id
    ORDER BY c.category_name
";
$statement = $pdo->query($query);
$skills = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $skills[$row['category_name']][] = $row['Skill_name'];
}

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// HTML content
$html = '
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { text-align: center; }
                .job-history { margin-bottom: 20px; }
                .job-title { font-weight: bold; }
                .job-description { margin-left: 20px; }
            </style>
        </head>
        <body>
            <h1>' . htmlspecialchars($profile['profile_name']) . '</h1>
            <p><strong>' . htmlspecialchars($profile['profile_description']) . '</strong></p>
            <p><strong>Email:</strong> ' . htmlspecialchars($profile['email_address']) . '</p>
            <p><strong>GitHub:</strong> <a href="' . htmlspecialchars($profile['github_url']) . '" target="_blank">' . htmlspecialchars($profile['github_url']) . '</a></p>
            <p><strong>LinkedIn:</strong> <a href="' . htmlspecialchars($profile['linkedin_url']) . '" target="_blank">' . htmlspecialchars($profile['linkedin_url']) . '</a></p>
            <p><strong>Website:</strong> <a href="' . htmlspecialchars($profile['website_url']) . '" target="_blank">' . htmlspecialchars($profile['website_url']) . '</a></p>

            <div class="job-history">
                <h2>Job History</h2>';

foreach ($job_history as $job) {
    $html .= '<p class="job-title">' . $job['job_title'] . ' at ' . $job['company_name'] . '</p>';
    $html .= '<p>From: ' . $job['start_date'] . ' To: ' . $job['end_date'] . '</p>';
    $html .= '<p class="job-description">Description: ' . $job['job_description'] . '</p>';
}

$html .= '<h2>Skills</h2>';

foreach ($skills as $category => $items) {
    $html .= '<p><strong>' . $category . ':</strong> ' . implode(', ', $items) . '</p>';
}

$html .= '</body></html>';

// Load the HTML content
$dompdf->loadHtml($html);

// (Optional) Setup paper size (A4, Letter, etc.)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream('resume_dompdf.pdf');
?>
