<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

require_once 'utilities/db_connection.php';

// Fetch all records for the list
$records = [];
try {
    // Adjust the query to get all companies
    $stmt = $pdo->query("SELECT * FROM companies ORDER BY company_name ASC");
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
 <link rel="stylesheet" href="css/modal-style.css">
    <title>Companies</title>
    <style>
        textarea {
          display: none; /* Hide textarea initially, as it's replaced by CKEditor */
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            border-right: 4px solid #ccc;
            height: 90%;
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
	   height: 80%;
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
        <hr style="height:3px;border-width:0;color:white;background-color:blue">
        
        <?php if (!isset($record)): ?>
            <!-- Add New Company Form: visible by default -->
            <h2>Add New Company</h2>

            <?php if (!empty($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php elseif (isset($_GET['success'])): ?>
                <h2><p style='color: green;background-color: #afcca2;"'>Record saved successfully!</p></h2>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" value="" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>

                <label for="logo">Logo:</label>
                <input type="file" id="logo" name="logo" accept=".png, .jpg, .jpeg">

                <button type="submit">Save</button>
            </form>
        <?php endif; ?>

        <?php if (isset($record)): ?>
            <!-- Edit Company Form: Visible only if a company is selected for editing -->
            <h2>Edit Company</h2>

            <?php if (!empty($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php elseif (isset($_GET['success'])): ?>
                <h2><p style='color: green;background-color: #afcca2;"'>Record saved successfully!</p></h2>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($record['company_id']); ?>">

                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($record['company_name']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($record['Description']); ?></textarea>

                <label for="logo">Logo:</label>
                <?php if (!empty($record['logo'])): ?>
                    <p>Current Logo: <img src="images/<?php echo htmlspecialchars($record['logo']); ?>" alt="logo" style="max-width: 500px; max-height: 100px;"></p>
                <?php endif; ?>
                <input type="file" id="logo" name="logo" accept=".png, .jpg, .jpeg">

                <button type="submit">Save</button>
            </form>
        <?php endif; ?>
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
