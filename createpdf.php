<?php
// Include the HTML2PDF library
require('html2pdf/html2pdf.class.php');  // Ensure the html2pdf.class.php is included

// Check if form is submitted to trigger PDF generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $host = 'localhost';
    $dbname = 'resume';
    $username = 'pday';
    $password = 'quality';
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Create a new HTML2PDF instance
    $html2pdf = new HTML2PDF('P', 'A4', 'en');

    // Start building the content for the PDF
    $content = '<html><body>';
    
    // Fetch profile info (name, description, links)
    $stmt = $pdo->query("SELECT * FROM profile LIMIT 1");
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set title and personal info
    $content .= "<h1 style='text-align:center;'>{$profile['profile_name']}</h1>";
    $content .= "<p><strong>Tagline:</strong> {$profile['profile_description']}</p>";
    $content .= "<p><strong>Email:</strong> {$profile['email_address']}</p>";
    $content .= "<p><strong>GitHub:</strong> {$profile['github_url']}</p>";
    $content .= "<p><strong>LinkedIn:</strong> {$profile['linkedin_url']}</p>";
    $content .= "<p><strong>Website:</strong> {$profile['website_url']}</p>";

    // Add some space
    $content .= "<br/><br/>";

    // Fetch Job History - Group by company and ordered by most recent job
    $stmt = $pdo->query("SELECT * FROM Job_history ORDER BY company_name, start_date DESC");
    $job_history = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $job_history[$row['company_name']][] = $row;
    }

    // Job History section
    $content .= "<h2>Job History</h2>";

    // Output the job history by company, most recent first
    foreach ($job_history as $company => $jobs) {
        $content .= "<h3>{$company}</h3>";

        foreach ($jobs as $job) {
            $content .= "<p><strong>{$job['job_title']}</strong></p>";
            $content .= "<p><em>From: {$job['start_date']} To: {$job['end_date']}</em></p>";
            $content .= "<p><strong>Description:</strong></p>";

            // Keep the HTML formatting in the job description
            $content .= "<div>" . $job['job_description'] . "</div>";

            // Add some space between jobs
            $content .= "<br/>";
        }
    }

    // Skills section
    $content .= "<h2>Skills</h2>";

    // Fetch and group skills by category
    $stmt = $pdo->query("SELECT skills.Skill_name, categories.category_name
                         FROM skills
                         JOIN categories ON skills.Skill_category_id = categories.category_id
                         ORDER BY categories.category_name, skills.Skill_name");

    $skills_by_category = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $skills_by_category[$row['category_name']][] = $row['Skill_name'];
    }

    // Output the skills by category
    foreach ($skills_by_category as $category => $skills) {
        $content .= "<h3>{$category}</h3>";
        $content .= "<p>" . implode(', ', $skills) . "</p>";
        $content .= "<br/>";
    }

    // Close the HTML tags
    $content .= "</body></html>";

    // Write the content to the PDF
    $html2pdf->writeHTML($content);

    // Output the PDF
    $html2pdf->Output('resume.pdf', 'D');  // Forces download with the name "resume.pdf"
    exit;  // Ensure no HTML is output after the PDF is generated
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Resume</title>
</head>
<body>
    <h1>Welcome to Resume Generator</h1>
    
    <p>Click the button below to generate your resume:</p>

    <!-- Form to trigger PDF generation -->
    <form action="createpdf.php" method="post">
        <button type="submit">Generate Resume PDF</button>
    </form>

</body>
</html>
