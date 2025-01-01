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
            margin: 20px;
        }
        .job-list {
            margin-top: 20px;
        }
        .job-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
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
        .company-header {
            display: flex;
            align-items: center;
        }
        .company-logo {
            width: 350px;
            height: 350px;
            margin-right: 15px;


        }
    </style>
</head>
<body>
    <h1>Jobs at <?php echo htmlspecialchars($company_name); ?></h1>

    <?php if ($company_logo): ?>
        <img src="images/<?php echo htmlspecialchars($company_logo); ?>" alt="Company Logo" class="company-logo">
    <?php endif; ?>

    <?php if (!empty($jobs)): ?>
        <div class="job-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-item">
                    <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
                    <p><br></p>
                    <div class="job-description">
                        <?php echo $job['job_description']; // Output raw HTML content ?>
                    </div>
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($job['start_date']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($job['end_date']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No jobs found for this company.</p>
    <?php endif; ?>
</body>
</html>
