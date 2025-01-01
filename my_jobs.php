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
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'] ?? null;
    $job_title = $_POST['job_title'] ?? "";
    $job_description = $_POST['job_description'] ?? "";
    $start_date = $_POST['start_date'] ?? "";
    $end_date = $_POST['end_date'] ?? "";

    if (!empty($job_id) && !empty($job_title) && !empty($job_description) && !empty($start_date) && !empty($end_date)) {
        try {
            $stmt = $pdo->prepare("UPDATE Job_history SET
                job_title = :job_title,
                job_description = :job_description,
                start_date = :start_date,
                end_date = :end_date
                WHERE job_id = :job_id");
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->bindParam(':job_title', $job_title);
            $stmt->bindParam(':job_description', $job_description);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();

            $message = "Record updated successfully!";
        } catch (PDOException $e) {
            $message = "Error updating record: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Edit Records</title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Initialize TinyMCE on the job_description textarea
        tinymce.init({
            selector: '#job_description',
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
            menubar: false
        });
    </script>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
        }
        .list {
            width: 30%;
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
            width: 70%;
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
        <?php if ($record): ?>
            <h2>Edit Record</h2>
            <?php if (!empty($message)): ?>
                <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($record['job_id']); ?>">

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
        <?php else: ?>
            <p>Please select a record from the list to view its details.</p>
        <?php endif; ?>
    </div>
</body>
</html>
