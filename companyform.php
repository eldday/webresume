<?php
// Configuration for the database connection
$host = "localhost"; // Replace "$IP" with the actual host
$dbname = "resume";
$username = "pday";
$password = "quality";

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Initialize variables
$company_name = "";
$description = "";
$logo = "";
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'] ?? "";
    $description = $_POST['description'] ?? "";

    // File upload handling
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $uploadDir = __DIR__ . "/images/";
        $fileName = basename($_FILES['logo']['name']);
        $targetPath = $uploadDir . $fileName;
        $fileType = mime_content_type($_FILES['logo']['tmp_name']);

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            $message = "Invalid file type. Only PNG, JPG, and JPEG are allowed.";
        } else {
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
    } else {
        $message = "Please upload a logo.";
    }

    // Insert new record into the database
    if (!empty($company_name) && !empty($description) && !empty($logo)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO resume.companies (company_name, Description, Logo) VALUES (:company_name, :description, :logo)");
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':logo', $logo);
            $stmt->execute();

            $message = "Company added successfully!";
            $company_name = $description = $logo = ""; // Reset fields after successful insert
        } catch (PDOException $e) {
            $message = "Error adding company: " . htmlspecialchars($e->getMessage());
        }
    } elseif (empty($message)) {
        $message = "Please fill in all fields and upload a logo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        textarea {
            resize: vertical;
            height: 150px;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
        img {
            max-width: 200px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Add New Company</h1>

    <?php if (!empty($message)): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" maxlength="4000" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="form-group">
            <label for="logo">Logo:</label>
            <input type="file" id="logo" name="logo" accept=".png, .jpg, .jpeg" required>
        </div>

        <button type="submit">Add Company</button>
    </form>

    <?php if (!empty($logo)): ?>
        <h3>Uploaded Logo:</h3>
        <img src="images/<?php echo htmlspecialchars($logo); ?>" alt="Company Logo">
    <?php endif; ?>
</body>
</html>
