
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
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 0;
                    line-height: 1.2;
                }
                h1 { 
                    text-align: center; 
                    margin: 10px 0;
                }
                p { 
                    margin: 5px 0;
                    font-size: 12px;
                }
                .job-history { 
                    margin-bottom: 15px;
                }
                .job-title-wrapper { 
                    font-size: 16px; /* Larger font size */
                    font-weight: bold;
                    margin: 5px 0;
                    color: white; /* White text on gradient background */
                    background-color: gray; /* Gradient from light to dark blue */
                    padding: 8px; /* Padding to give space around the text */
                    border-radius: 5px; /* Optional: rounded corners */
                    margin-bottom: 5px;
                }
                .job-description { 
                    margin-left: 0px; 
                    font-size: 12px;
                }
                hr {
                    border: 0; 
                    border-top: 1px solid #000; 
                    margin: 10px 0;
                }
                table {
                    width: 100%; 
                    border-spacing: 0;
                    margin-bottom: 10px;
                }
                td {
                    padding: 5px;
                    vertical-align: middle;
                }
                .job-title-cell {
                    font-size: 16px;
                    font-weight: bold;
                    color: white;
                    background-image: linear-gradient(to right, #6fa3ef, #1e60ab);
                    padding: 8px;
                }
                .job-dates-cell {
                    text-align: right;
                    padding: 8px;
                    background-image: linear-gradient(to right, #6fa3ef, #1e60ab);
                    color: white;
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

   if ($last_company !== $job['company_name']) {
       if ($last_company !== null) {
           $html .= '<hr>';
       }
       $last_company = $job['company_name'];
   }

   $html .= '<table>
                <tr class="job-title-wrapper">
                    <td class="job-title-cell">' . htmlspecialchars($job['job_title']) . ' at ' . htmlspecialchars($job['company_name']) . '</td>
                    <td class="job-dates-cell"><strong>From: </strong>' . $start_date . ' <strong>To: </strong>' . $end_date . '</td>
                </tr>
              </table>';
   $html .= '<p class="job-description">' . htmlspecialchars_decode($job['job_description']) . '</p>';
}

$html .= '</div>';

$html .= '<h2>Skills</h2>';

foreach ($skills as $category => $items) {
    $html .= '<p><strong>' . htmlspecialchars($category) . ':</strong> ' . implode(', ', array_map('htmlspecialchars', $items)) . '</p>';
}

$html .= '</body></html>';


// Load the HTML content
$dompdf->loadHtml($html);

// (Optional) Setup paper size (A4, Letter, etc.)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream(htmlspecialchars($profile['profile_name']) . '-resume-as-pdf.pdf');
?>
