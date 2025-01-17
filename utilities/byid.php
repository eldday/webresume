<?php
// Include database connection
require_once 'db_connection.php';

// Fetch company details and jobs if a company ID is provided
$company_id = $_GET['company_id'] ?? null;
$company_name = '';
$company_logo = '';
$company_description = '';
$jobs = [];

if ($company_id && is_numeric($company_id)) {
    try {
        // Query to fetch company details (name, logo, and description)
        $companyStmt = $pdo->prepare("
            SELECT company_name, logo, Description
            FROM companies
            WHERE company_id = :company_id
        ");
        $companyStmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $companyStmt->execute();
        $company = $companyStmt->fetch(PDO::FETCH_ASSOC);

        // If company exists, set the company name, logo, and description
        if ($company) {
            $company_name = $company['company_name'];
            $company_logo = $company['logo'];
            $company_description = $company['Description'];
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
        .container {
            display: flex;
            align-items: flex-start;
}
        .company-info {
            margin-right: 30px;
            text-align: center;
        }
        .company-logo {
            width: 100%;
	    height: 100%;
            cursor: pointer;
        }
        .job-list {
            flex-grow: 5;
	    height: 100%;
        }
        .job-item {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .job-item h2 {
            margin: 0;
            font-size: 18px;
            color: DodgerBlue;
        }
        .job-description {
            margin-top: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 10;
            left: 0;
            width: 60%;
            height: 60%;
            //background-color: rgba(0, 0, 0, 0.5);
            justify-contsent: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            text-align: auto;
	    overflow-y: auto;
        }
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .modal-close {
            cursor: pointer;
            float: right;
            font-size: 20px;
        }
    </style>
</head>
<body>
<br><br><br>
    <div class="container">
        <!-- Company info and logo column -->
        <div class="company-info">
            <?php if ($company_logo): ?>
                <img src="images/<?php echo htmlspecialchars($company_logo); ?>" alt="Company Logo" class="company-logo" onclick="openModal()">
            <?php endif; ?>
        </div>

        <!-- Job listings column -->
        <div class="job-list">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-item">
                        <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
                        <p><strong>From:</strong> <?php echo htmlspecialchars($job['start_date']); ?> <strong>To:</strong> <?php echo htmlspecialchars($job['end_date']); ?></p>
                        <div class="job-description">
                            <?php echo $job['job_description']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No jobs found for this company.</p>
            <?php endif; ?>
        </div>
    </div>

<div id="modal" class="modal">
    <div id="modal-content" class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-header"><?php echo htmlspecialchars($company_name); ?></div>
        <p><?php echo $company_description;?></p>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        max-height: 80%;
        overflow-y: auto;
        text-align: left;
        position: relative; /* Allow absolute positioning for dragging */
        border: 2px solid #ccc; /* Add a border */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); /* Add shadow */
        cursor: move; /* Indicate that the modal is draggable */
    }
    .modal-header {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .modal-close {
        cursor: pointer;
        float: right;
        font-size: 50px;
    }
</style>

<script>
    // Open modal
    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }

    // Close modal
    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    // Make the modal draggable
    const modalContent = document.getElementById('modal-content');
    let isDragging = false;
    let offsetX = 0;
    let offsetY = 0;

    modalContent.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - modalContent.getBoundingClientRect().left;
        offsetY = e.clientY - modalContent.getBoundingClientRect().top;
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);
    });

    function handleMouseMove(e) {
        if (!isDragging) return;
        modalContent.style.position = 'absolute';
        modalContent.style.left = `${e.clientX - offsetX}px`;
        modalContent.style.top = `${e.clientY - offsetY}px`;
    }

    function handleMouseUp() {
        isDragging = false;
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseup', handleMouseUp);
    }
</script>

    <script>
        // Open modal
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }

        // Close modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</body>
</html>
