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

// Fetch company jobs if a company ID is provided
$company_id = $_GET['company_id'] ?? null;
$jobs = [];

if ($company_id && is_numeric($company_id)) {
    try {
        // Query to fetch jobs related to the company, ordered by end_date descending
        $stmt = $pdo->prepare("
            SELECT job_title, job_description, start_date, end_date
            FROM Job_history
            WHERE company_id = :company_id
            ORDER BY end_date DESC
        ");
        $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->execute();
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching jobs: " . $e->getMessage());
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

	</style>
</head>
<body>
    <h1>Jobs for Company ID: <?php echo htmlspecialchars($company_id); ?></h1>

    <?php if (!empty($jobs)): ?>
        <div class="job-list">
            <?php foreach ($jobs as $job): ?>
<div class="job-item">
    <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
    <p><strong>Description:</strong></p>
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
