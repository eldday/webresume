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


// Fetch all records for the list
$records = [];
try {
    $stmt = $pdo->query("SELECT job_id, job_title FROM Job_history");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}

// Fetch a specific record if requested
$record = null;
if (isset($_GET['job_id']) && is_numeric($_GET['job_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM Job_history WHERE job_id = :job_id");
        $stmt->bindParam(':job_id', $_GET['job_id'], PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching record: " . $e->getMessage());
    }
}

// Handle form submission for updating a record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            header("Location: jobs.php?job_id=$job_id&success=1");
        exit;
        } catch (PDOException $e) {
            $message = "Error updating record: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}


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
            $stmt->bindParam(':company_id', $company_id);
            $stmt->bindParam(':job_title', $job_title);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error adding job: " . $e->getMessage());
        }
    }
}

if (isset($_POST['add_company'])) {
    $company_name = $_POST['company_name'] ?? "";
    $description = $_POST['description'] ?? "";

    if ($company_name && $description) {
        try {
            $stmt = $pdo->prepare("INSERT INTO resume.companies (company_name, Description)
                VALUES (:company_name, :description)");
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error adding company: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs and Companies</title>
    <!-- Include CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
        }
        .list {
            width: 20%;
            border-right: 1px solid #ccc;
            padding: 10px;
        }
        .list ul {
            list-style: none;
            padding: 0;
        }
        .list ul li {
            margin: 5px 0;
        }
        .list ul li a {
            text-decoration: none;
            color: blue;
        }
        .details {
            width: 80%;
            padding: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
        <!-- Edit Job Form -->
        <?php if ($record): ?>
            <h2>Edit Job</h2>
            <form method="POST" action="">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($record['job_id']); ?>">

                <label for="company_id">Company:</label>
                <select id="company_id" name="company_id" required>
                    <option value="">Select a company</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo htmlspecialchars($company['company_id']); ?>"
                            <?php echo ($record['company_id'] == $company['company_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($company['company_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="job_title">Job Title:</label>
                <input type="text" id="job_title" name="job_title"
                    value="<?php echo htmlspecialchars($record['job_title']); ?>" required>

                <label for="job_description">Job Description:</label>
                <textarea id="job_description" name="job_description" required><?php echo htmlspecialchars($record['job_description']); ?></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date"
                    value="<?php echo htmlspecialchars($record['start_date']); ?>" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date"
                    value="<?php echo htmlspecialchars($record['end_date']); ?>" required>

                <button type="submit">Update</button>
            </form>
        <?php endif; ?>

        <!-- Add New Job -->
        <h2>Add New Job</h2>
        <form method="POST" action="">
            <label for="company_id">Company:</label>
            <select id="company_id" name="company_id" required>
                <option value="">Select a company</option>
                <?php foreach ($companies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company['company_id']); ?>">
                        <?php echo htmlspecialchars($company['company_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" required>

            <label for="job_description">Job Description:</label>
            <textarea id="job_description" name="job_description" required></textarea>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="submit" name="add_job">Add Job</button>
        </form>

        <!-- Add New Company -->
        <h2>Add New Company</h2>
        <form method="POST" action="">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <button type="submit" name="add_company">Add Company</button>
        </form>
    </div>

    <script>
        ClassicEditor
            .create(document.querySelector('#job_description'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
