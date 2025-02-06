<?php
// Include database connection
include('utilities/db_connection.php');

// Start session if you want to use session variables for user login (optional)
session_start();

// Get the profile, job history, and skills from the database
function getProfile($profile_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
    $stmt->execute(['profile_id' => $profile_id]);
    return $stmt->fetch();
}

function getJobHistory($profile_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Job_history WHERE company_id IN (SELECT company_id FROM companies WHERE company_id IN (SELECT company_id FROM Job_history WHERE company_id = :company_id)) ORDER BY start_date DESC");
    $stmt->execute(['company_id' => $profile_id]);
    return $stmt->fetchAll();
}

function getSkills($profile_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT skills.Skill_name, categories.category_name FROM skills INNER JOIN categories ON skills.Skill_category_id = categories.category_id WHERE skills.profile_id = :profile_id");
    $stmt->execute(['profile_id' => $profile_id]);
    return $stmt->fetchAll();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected options
    $include_profile = isset($_POST['profile']) ? true : false;
    $include_job_history = isset($_POST['job_history']) ? true : false;
    $include_skills = isset($_POST['skills']) ? true : false;

    // Generate PDF based on selected options
    require_once 'utilities/html2pdf.class.php'; // Make sure this path is correct to your HTML2PDF library

    try {
        ob_start();
        
        // Get data from database
        $profile = getProfile(1); // Example profile_id = 1, adjust as necessary
        $job_history = getJobHistory(1); // Example profile_id = 1, adjust as necessary
        $skills = getSkills(1); // Example profile_id = 1, adjust as necessary

        ?>

        <html>
        <head>
            <title>Resume</title>
        </head>
        <body>
            <h1>Resume of <?php echo $profile['profile_name']; ?></h1>

            <?php if ($include_profile): ?>
                <h2>Profile</h2>
                <p><strong>About Me:</strong> <?php echo $profile['profile_description']; ?></p>
                <p><strong>Email:</strong> <?php echo $profile['email_address']; ?></p>
                <p><strong>GitHub:</strong> <a href="<?php echo $profile['github_url']; ?>" target="_blank"><?php echo $profile['github_url']; ?></a></p>
                <p><strong>LinkedIn:</strong> <a href="<?php echo $profile['linkedin_url']; ?>" target="_blank"><?php echo $profile['linkedin_url']; ?></a></p>
                <p><strong>Website:</strong> <a href="<?php echo $profile['website_url']; ?>" target="_blank"><?php echo $profile['website_url']; ?></a></p>
            <?php endif; ?>

            <?php if ($include_job_history): ?>
                <h2>Job History</h2>
                <?php foreach ($job_history as $job): ?>
                    <h3><?php echo $job['job_title']; ?> at <?php echo $job['company_name']; ?></h3>
                    <p><strong>Start Date:</strong> <?php echo $job['start_date']; ?> | <strong>End Date:</strong> <?php echo $job['end_date']; ?></p>
                    <div><?php echo $job['job_description']; ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($include_skills): ?>
                <h2>Skills</h2>
                <ul>
                    <?php foreach ($skills as $skill): ?>
                        <li><?php echo $skill['Skill_name']; ?> (Category: <?php echo $skill['category_name']; ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </body>
        </html>

        <?php
        $content = ob_get_clean();

        // Convert to PDF
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('resume.pdf');
        exit();
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
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
    <h1>Select Resume Options</h1>
    <form method="POST">
        <label>
            <input type="checkbox" name="profile" checked> Include Profile
        </label><br>
        <label>
            <input type="checkbox" name="job_history" checked> Include Job History
        </label><br>
        <label>
            <input type="checkbox" name="skills" checked> Include Skills
        </label><br>
        <input type="submit" value="Generate Resume">
    </form>
</body>
</html>
