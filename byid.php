<?php
// Configuration for the database connection
$host = "$IP";
$dbname = "resume";
$username = "pday";
$password = "quality";

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch company details and jobs if a company ID is provided
$company_id = $_GET['company_id'] ?? null;
$company_name = '';
$company_logo = '';
$jobs = [];

if ($company_id && is_numeric($company_id)) {
    try {
        // Query to fetch company details (name and logo)
        $companyStmt = $pdo->prepare("
            SELECT company_name, logo
            FROM companies
            WHERE company_id = :company_id
        ");
        $companyStmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $companyStmt->execute();
        $company = $companyStmt->fetch(PDO::FETCH_ASSOC);
        
        // If company exists, set the company name and logo
        if ($company) {
            $company_name = $company['company_name'];
            $company_logo = $company['logo'];
        }

        // Query to fetch jobs related to the company, ordered by end_date descending
        $jobStmt = $pdo->prepare("
            SELECT job_title, job_description, start_date, end_date
            FROM Job_history
            WHERE company_id = :company_id
            ORDER BY end_date DESC
        ");
        $jobStmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $jobStmt->execute();
        $jobs = $jobStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching company details or jobs: " . $e->getMessage());
    }
} else {
    die("Invalid or missing company ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
	    margin-top: 20px;
            margin: 20px;
        }
        .container {
            display: flex; /* Flexbox container */
            align-items: flex-start;
        }
        .company-info {
            margin-right: 30px; /* Space between logo and jobs */
            text-align: center;
        }
        .company-logo {
            width: 100%;  /* Increased size */
            height: 100%; /* Increased size */
            margin-bottom: 10px;
        }
        .job-list {
            flex-grow: 1; /* Jobs take up the remaining space */
        }
        .job-item {
            border: 0px solid #FFF;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .job-item h2 {
            margin: 0;
            font-size: 18px;
        }
        .job-item p {
            margin: 5px 0;
        }
        .job-description ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .job-description li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<br><br><br><br>
    <div class="container">
        <!-- Company info and logo column -->
        <div class="company-info">
            <?php if ($company_logo): ?>
                <img src="images/<?php echo htmlspecialchars($company_logo); ?>" alt="Company Logo" class="company-logo">
            <?php endif; ?>
        </div>

        <!-- Job listings column -->
        <div class="job-list">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-item">
                        <h2 style="color:DodgerBlue;"><?php echo htmlspecialchars($job['job_title']); ?></h2>
                       <div style="display: flex; justify-content: space-around"><p><strong><br></strong></p> <p><strong>From:</strong> <?php echo htmlspecialchars($job['start_date']);?> <strong> To: </strong><?php echo htmlspecialchars($job['end_date']); ?></p></div>
                        <div class="job-description">
                            <?php echo $job['job_description']; // Output raw HTML content ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No jobs found for this company.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
