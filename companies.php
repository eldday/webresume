<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

require_once 'db_connection.php';

// Fetch all records for the list
$records = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT c.company_name, c.company_id FROM companies c INNER JOIN Job_history j ON c.company_id = j.company_id ORDER BY end_date DESC;");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . $e->getMessage());
}

// Fetch a specific record if requested
$record = null;
if (isset($_GET['company_id']) && is_numeric($_GET['company_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM companies WHERE company_id = :company_id");
        $stmt->bindParam(':company_id', $_GET['company_id'], PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching record: " . $e->getMessage());
    }
}

// Handle form submission for updating or inserting a record
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'] ?? null;
    $company_name = $_POST['company_name'] ?? "";
    $description = $_POST['description'] ?? "";
    if (empty($description)) {
        $message = "Description cannot be empty.";
    }
    $logo = $record['logo'] ?? ""; // Existing logo filename

    // Handle file upload if a new logo is provided
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/images/";
        $fileName = basename($_FILES['logo']['name']);
        $targetPath = $uploadDir . $fileName;

        // Ensure the images directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
            $logo = $fileName;
        } else {
            $message = "Error uploading logo.";
        }
    }

    // Perform database update
    if ($company_id) {
        try {
            $stmt = $pdo->prepare("UPDATE companies SET
                company_name = :company_name,
                Description = :description,
                Logo = :logo
                WHERE company_id = :company_id");
            $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':logo', $logo);
            $stmt->execute();

            header("Location: companies.php?company_id=$company_id&success=1");
            exit;
        } catch (PDOException $e) {
            $message = "Error updating record: " . $e->getMessage();
        }
    } else {
        // Insert new record
        try {
            $stmt = $pdo->prepare("INSERT INTO companies (company_name, Description, Logo) VALUES (:company_name, :description, :logo)");
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':logo', $logo);
            $stmt->execute();

            header("Location: companies.php?success=1");
            exit;
        } catch (PDOException $e) {
            $message = "Error inserting record: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <title>Companies</title>
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
        <h2>Companies</h2>
        <ul>
            <?php foreach ($records as $recordItem): ?>
                <li>
                    <a href="?company_id=<?php echo htmlspecialchars($recordItem['company_id']); ?>">
                        <?php echo htmlspecialchars($recordItem['company_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="details">
        <?php if ($record): ?>
            <h2>Edit Company</h2>
        <?php else: ?>
            <h2>Add New Company</h2>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
        <?php elseif (isset($_GET['success'])): ?>
            <p style="color: green;">Record saved successfully!</p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <?php if ($record): ?>
                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($record['company_id']); ?>">
            <?php endif; ?>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name"
                value="<?php echo htmlspecialchars($record['company_name'] ?? ''); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($record['Description'] ?? ''); ?></textarea>

            <label for="logo">Logo:</label>
            <?php if (!empty($record['logo'])): ?>
                <p>Current Logo: <img src="images/<?php echo htmlspecialchars($record['logo']); ?>" alt="logo" style="max-width: 500px; max-height: 100px;"></p>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept=".png, .jpg, .jpeg">

            <button type="submit">Save</button>
        </form>
    </div>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                editor.setData(<?php echo json_encode($record['Description'] ?? ''); ?>);

                document.querySelector('form').addEventListener('submit', () => {
                    document.querySelector('#description').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>
