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

// Initialize variables
$company_name = "";
$description = "";
$logo = "";
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'] ?? "";
    $description = $_POST['description'] ?? "";

    // Handle file upload
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
            $message = "Error adding company: " . $e->getMessage();
        }
    } else {
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .logo-preview img {
            max-width: 150px;
            max-height: 150px;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Add New Company</h1>

    <?php if (!empty($message)): ?>
        <p class="<?php echo empty($logo) ? 'error' : 'message'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>

        <label for="logo">Logo:</label>
        <input type="file" id="logo" name="logo" accept=".png, .jpg, .jpeg" required>

        <?php if (!empty($logo)): ?>
            <div class="logo-preview">
                <p>Uploaded Logo:</p>
                <img src="images/<?php echo htmlspecialchars($logo); ?>" alt="Company Logo">
            </div>
        <?php endif; ?>

        <button type="submit">Add Company</button>
    </form>
</body>
</html>

