<?php
require_once 'utilities/db_connection.php';

session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

// Fetch all job records
$records = [];
try {
    $stmt = $pdo->query("SELECT job_id, job_title FROM Job_history ORDER BY start_date DESC");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching job records: " . $e->getMessage());
}

// Fetch all companies
$companies = [];
try {
    $stmt = $pdo->query("SELECT company_id, company_name FROM companies");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching companies: " . $e->getMessage());
}

// Fetch a specific job record if requested
$record = null;
if (isset($_GET['job_id']) && is_numeric($_GET['job_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM Job_history WHERE job_id = :job_id");
        $stmt->bindParam(':job_id', $_GET['job_id'], PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching job record: " . $e->getMessage());
    }
}

// Handle form submissions
$message = "";

// Update a job record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'] ?? null;
    $company_id = $_POST['company_id'] ?? null;
    $job_title = $_POST['job_title'] ?? "";
    $job_description = $_POST['job_description'] ?? "";
    $start_date = $_POST['start_date'] ?? "";
    $end_date = $_POST['end_date'] ?? "";

    if ($job_id && $company_id && $job_title && $job_description && $start_date && $end_date) {
        try {
            $stmt = $pdo->prepare("UPDATE Job_history SET
                company_id = :company_id,
                job_title = :job_title,
                job_description = :job_description,
                start_date = :start_date,
                end_date = :end_date
                WHERE job_id = :job_id");
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->bindParam(':job_title', $job_title);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            $message = '<p style="color: green;background-color: #afcca2;">Job updated successfully!</p>';
        } catch (PDOException $e) {
            $message = '<p style="color: red;background-color: #dfa8bb">Error updating job: ' . $e->getMessage() . '</p>';
        }
    } else {
        $message = '<p style="color: red;background-color: #dfa8bb">Please fill in all fields.</p>';
    }
}

// Add a new job
if (isset($_POST['add_job'])) {
    $company_id = $_POST['company_id'] ?? null;
    $job_title = $_POST['job_title'] ?? "";
    $job_description = $_POST['job_description'] ?? "";
    $start_date = $_POST['start_date'] ?? "";
    $end_date = $_POST['end_date'] ?? "";

    if ($company_id && $job_title && $job_description && $start_date && $end_date) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Job_history (company_id, job_title, job_description, start_date, end_date)
                VALUES (:company_id, :job_title, :job_description, :start_date, :end_date)");
            $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->bindParam(':job_title', $job_title);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            $message = '<p style="color: green;background-color: #afcca2;">Job added successfully!</p>';
        } catch (PDOException $e) {
            $message = '<p style="color: red;background-color: #dfa8bb">Error adding job: ' . $e->getMessage() . '</p>';
        }
    } else {
        $message = '<p style="color: red; background-color: #dfa8bb;">Please fill in all fields.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs and Companies</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    //<link rel="stylesheet" href="css/modal-style.css">
    <style>
	textarea {
          display: none; /* Hide textarea initially, as it's replaced by CKEditor */
	}

	body {
            display: flex;
            font-family: Arial, sans-serif;
	    border-right: 4px solid #ccc;
        }
        .details {
            width: 80%;
            padding: 20px;
	    margint-right: 20px;
	    margin-left: 20px;
        }
        .sidebar {
            width: 20%;
            padding: 20px;
            border-right: 4px solid #ccc;
	    border-left: 4px solid #ccc;
        }
        .main {
            flex-grow: 1;
            padding: 5px;
        }
        .user-list {
            list-style: none;
            padding: 5;
        }
        .user-list li {
            margin-bottom: 5px;
            background-color: rgba(0, 0, 0, 0.059);
            border: solid #ffffff1f 0.75pt;
            border-width: 15 15 1px 0; /* Bottom border only */
            line-height: 1.2;
            margin-bottom: 5px;
            padding: 6pt 4pt;
            text-indent: 0;
            font-size: 16px;
            color: #333;
        }
        .list {
            width: 25%;
            border-right: 2px solid #ccc;
            border-left: 2px solid #ccc;
	    padding: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
        }
        textarea {
            display: block; /* Ensure the textarea is visible initially */
	   margin: 10px; 
       }
    </style>
</head>
<body>

    <div class="list">
        <h2>Job History</h2>
        <ul>
            <?php foreach ($records as $recordItem): ?>
                <li>
                    <a href="?job_id=<?php echo htmlspecialchars($recordItem['job_id']); ?>">
                        <?php echo htmlspecialchars($recordItem['job_title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="details">
         <h2><?php echo $message; ?></h2>
	<!-- Edit Job Form -->
        <?php if ($record): ?>
            <hr style="height:3px;border-width:0;color:white;background-color:blue">
            <h2>Edit Job</h2>
            <form method="POST">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($record['job_id']); ?>">

                <label for="company_id">Company:</label>
                <select id="company_id" name="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo htmlspecialchars($company['company_id']); ?>"
                            <?php echo ($record['company_id'] == $company['company_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($company['company_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="job_title">Job Title:</label>
                <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($record['job_title']); ?>" required>

                <label for="job_description">Job Description:</label>
                <textarea id="job_description" name="job_description" required><?php echo htmlspecialchars($record['job_description']); ?></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($record['start_date']); ?>" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($record['end_date']); ?>" required>

                <button type="submit">Update</button>
            </form>
        <?php endif; ?>

        <!-- Add New Job -->
        <?php if (!$record): ?>
            <hr style="height:3px;border-width:0;color:white;background-color:blue">
            <h2>Add New Job</h2>
            <form id="add-job-form" method="POST">
                <label for="company_id_new">Company:</label>
                <select id="company_id_new" name="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo htmlspecialchars($company['company_id']); ?>">
                            <?php echo htmlspecialchars($company['company_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="job_title_new">Job Title:</label>
                <input type="text" id="job_title_new" name="job_title" required>

                <label for="job_description_new">Job Description:</label>
                <textarea id="job_description_new" name="job_description_new" required></textarea>  

                <label for="start_date_new">Start Date:</label>
                <input type="date" id="start_date_new" name="start_date" required>

                <label for="end_date_new">End Date:</label>
                <input type="date" id="end_date_new" name="end_date" required>

            <br><button type="submit" name="add_job">Add Job</button>
            </form>
        <?php endif; ?>
    </div>
	<script>
document.addEventListener("DOMContentLoaded", function () {
    let editors = {};

    // Initialize CKEditor for both job_description and job_description_new
    ['job_description', 'job_description_new'].forEach(id => {
        let field = document.getElementById(id);
        if (field) {
            // Initialize ClassicEditor on the textarea
            ClassicEditor.create(field).then(editor => {
                editors[id] = editor; // Store the editor instance
                // After initialization, hide the textarea and remove required attribute
                field.style.display = 'none'; // Hide the original textarea
                field.removeAttribute('required'); // Remove the 'required' attribute
            }).catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
        }
    });

    // Ensure the form submission only proceeds when CKEditor is initialized
    let addJobForm = document.getElementById("add-job-form");
    if (addJobForm) {
        addJobForm.addEventListener("submit", function (event) {
            // Check if all CKEditor instances are initialized and ready
            if (!editors['job_description_new']) {
                event.preventDefault();
                alert("Please wait for CKEditor to initialize.");
            }
        });
    }
});

    	</script>
</body>
</html>
