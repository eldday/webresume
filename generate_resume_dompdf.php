
<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
require 'utilities/db_connection.php';

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
                .job-history { margin-bottom: 10px; }
                .job-title { font-size: 120%; font-weight: bold; }
                .job-description { margin-left: 0px; }
            </style>
        </head>
        <body>
            <h1>' . htmlspecialchars($profile['profile_name']) . '</h1>
            <p><center><strong>' . htmlspecialchars($profile['profile_description'] ?? 'No description available') . '</strong></center></p>
            <p><strong>Email:</strong> ' . htmlspecialchars($profile['email_address'] ?? 'N/A') . '</p>
            <p><strong>GitHub:</strong> <a href="' . htmlspecialchars($profile['github_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['github_url'] ?? 'N/A') . '</a></p>
            <p><strong>LinkedIn:</strong> <a href="' . htmlspecialchars($profile['linkedin_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['linkedin_url'] ?? 'N/A') . '</a></p>
            <p><strong>Website:</strong> <a href="' . htmlspecialchars($profile['website_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['website_url'] ?? 'N/A') . '</a></p>

            <div class="job-history">
                <h2>Job History</h2>';

$html = '
    <html>
        <head>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 0;
                    line-height: 1.2; /* Reduce line height */
                }
                h1 { 
                    text-align: center; 
                    margin: 10px 0; /* Reduced margin */
                }
                p { 
                    margin: 5px 0; /* Reduced margin between paragraphs */
                    font-size: 12px; /* Slightly smaller font size */
                }
                .job-history { 
                    margin-bottom: 15px; /* Reduced margin between job sections */
                }
                .job-title { 
                    font-size: 110%; 
                    font-weight: bold;
                    margin: 5px 0; /* Reduced margin */
                }
                .job-description { 
                    margin-left: 0px; 
                    font-size: 12px; /* Slightly smaller font size for description */
                }
                hr {
                    border: 0; 
                    border-top: 1px solid #000; 
                    margin: 10px 0; /* Reduced margin for horizontal line */
                }
                .contact-info {
                    font-size: 12px;
                    margin: 5px 0; /* Reduced margin between contact fields */
                }
            </style>
        </head>
        <body>
            <h1>' . htmlspecialchars($profile['profile_name']) . '</h1>
            <p><center><strong>' . htmlspecialchars($profile['profile_description'] ?? 'No description available') . '</strong></center></p>
            <div class="contact-info">
                <p><strong>Email:</strong> ' . htmlspecialchars($profile['email_address'] ?? 'N/A') . '</p>
                <p><strong>GitHub:</strong> <a href="' . htmlspecialchars($profile['github_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['github_url'] ?? 'N/A') . '</a></p>
                <p><strong>LinkedIn:</strong> <a href="' . htmlspecialchars($profile['linkedin_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['linkedin_url'] ?? 'N/A') . '</a></p>
                <p><strong>Website:</strong> <a href="' . htmlspecialchars($profile['website_url'] ?? '#') . '" target="_blank">' . htmlspecialchars($profile['website_url'] ?? 'N/A') . '</a></p>
            </div>

            <div class="job-history">
                <h2>Job History</h2>';

$last_company = null;

foreach ($job_history as $job) {
   $start_date = DateTime::createFromFormat('Y-m-d', $job['start_date'])->format('m/Y');
   $end_date = DateTime::createFromFormat('Y-m-d', $job['end_date'])->format('m/Y');

   // Add horizontal line when the company changes
   if ($last_company !== $job['company_name']) {
       if ($last_company !== null) {
           $html .= '<hr>';
       }
       $last_company = $job['company_name'];
   }

   // Job Title and Company
   $html .= '<div style="display: flex; justify-content: space-between; align-items: center;">
                <p class="job-title" style="margin: 0; flex-grow: 1;">' . htmlspecialchars($job['job_title']) . ' at ' . htmlspecialchars($job['company_name']) . '</p>
                <p style="text-align: right; margin: 0;"><strong>From: </strong>' . $start_date . ' <strong>To: </strong>' . $end_date . '</p>
              </div>';

   // Use htmlspecialchars_decode to preserve HTML formatting in job description
   $html .= '<p class="job-description">' . htmlspecialchars_decode($job['job_description']) . '</p>';
}

$html .= '</div>';

$html .= '<h2>Skills</h2>';

foreach ($skills as $category => $items) {
    $html .= '<p><strong>' . htmlspecialchars($category) . ':</strong> ' . implode(', ', array_map('htmlspecialchars', $items)) . '</p>';
}

$html .= '</body></html>';

//foreach ($job_history as $job) {
//   $start_date = DateTime::createFromFormat('Y-m-d', $job['start_date'])->format('m/Y');
//   $end_date = DateTime::createFromFormat('Y-m-d', $job['end_date'])->format('m/Y');

//   $html .= '<div style="display: flex; justify-content: space-between; align-items: center;">
//                <p class="job-title" style="margin: 0; flex-grow: 1;">' . htmlspecialchars($job['job_title']) . ' at ' . htmlspecialchars($job['company_name']) . '</p>
//                <p style="text-align: right; margin: 0;"><strong>From: </strong>' . $start_date . ' <strong>To: </strong>' . $end_date . '</p>
//              </div>';
//   $html .= '<p class="job-description">Description:' . $job['job_description'] . '</p>';
//   $html .= '<hr style="border: 1px solid #000; margin-top: 10px; margin-bottom: 10px;">';
//$last_company = null;  // Initialize a variable to track the last company

//foreach ($job_history as $job) {
   // Format the start and end dates
//   $start_date = DateTime::createFromFormat('Y-m-d', $job['start_date'])->format('m/Y');
//   $end_date = DateTime::createFromFormat('Y-m-d', $job['end_date'])->format('m/Y');
   
   // Check if the company has changed
//   if ($last_company !== $job['company_name']) {
       // If it's a new company, and it's not the first company, add a horizontal line
 //      if ($last_company !== null) {
//           $html .= '<hr style="border: 1px solid #000; margin-top: 10px; margin-bottom: 10px;">';
//       }
       // Update the last_company to the current one
//       $last_company = $job['company_name'];
//   }

   // Add the job details for the current company
//   $html .= '<div style="display: flex; justify-content: space-between; align-items: center;">
//                <p class="job-title" style="margin: 0; flex-grow: 1;">' . htmlspecialchars($job['job_title']) . ' at ' . htmlspecialchars($job['company_name']) . '</p>
//                <p style="text-align: right; margin: 0;"><strong>From: </strong>' . $start_date . ' <strong>To: </strong>' . $end_date . '</p>
//              </div>';
//   $html .= '<p class="job-description">Description:' . $job['job_description'] . '</p>';
//}


//$html .= '</div>';
//$html .= '<h2>Skills</h2>';

//foreach ($skills as $category => $items) {
//    $html .= '<p><strong>' . htmlspecialchars($category) . ':</strong> ' . implode(', ', array_map('htmlspecialchars', $items)) . '</p>';
//}

//$html .= '</body></html>';

// Load the HTML content
$dompdf->loadHtml($html);

// (Optional) Setup paper size (A4, Letter, etc.)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream(htmlspecialchars($profile['profile_name']) . '-resume-as-pdf.pdf');
?>
