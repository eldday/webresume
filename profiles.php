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
    $stmt = $pdo->query("SELECT * FROM profile;");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching records: " . htmlspecialchars($e->getMessage()));
}

// Fetch a specific record if requested
$record = null;
if (isset($_GET['profile_id']) && is_numeric($_GET['profile_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
        $stmt->bindParam(':profile_id', $_GET['profile_id'], PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching record: " . htmlspecialchars($e->getMessage()));
    }
}

// Handle form submission for updating or inserting a record
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile_id = $_POST['profile_id'] ?? null;
    $profile_name = $_POST['profile_name'] ?? "";
    $profile_description = $_POST['profile_description'] ?? "";
    $github_url = $_POST['github_url'] ?? "";
    $linkedin_url = $_POST['linkedin_url'] ?? "";
    $website_url = $_POST['website_url'] ?? "";
    $email_address = $_POST['email_address'] ?? "";
    $bg_image = $record['bg_image'] ?? ""; // Existing background image filename

    // Validate required fields
    if (empty($profile_name) || empty($profile_description)) {
        $message = "Name and Description cannot be empty.";
    }

    // Handle file upload if a new background image is provided
    if (isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/images/";
        $fileName = basename($_FILES['bg_image']['name']);
        $targetPath = $uploadDir . $fileName;

        // Ensure the images directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['bg_image']['tmp_name'], $targetPath)) {
            $bg_image = $fileName;
        } else {
            $message = "Error uploading background image.";
        }
    }

    // Perform database operations
    if (empty($message)) {
        try {
            if ($profile_id) {
                // Update record
                $stmt = $pdo->prepare("UPDATE profile SET
                    profile_name = :profile_name,
                    profile_description = :profile_description,
                    github_url = :github_url,
                    linkedin_url = :linkedin_url,
                    website_url = :website_url,
                    email_address = :email_address,
                    bg_image = :bg_image
                    WHERE profile_id = :profile_id");
                $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
            } else {
                // Insert new record
                $stmt = $pdo->prepare("INSERT INTO profile 
                    (profile_name, profile_description, github_url, linkedin_url, website_url, email_address, bg_image) 
                    VALUES (:profile_name, :profile_description, :github_url, :linkedin_url, :website_url, :email_address, :bg_image)");
            }

            // Bind common parameters
            $stmt->bindParam(':profile_name', $profile_name);
            $stmt->bindParam(':profile_description', $profile_description);
            $stmt->bindParam(':github_url', $github_url);
            $stmt->bindParam(':linkedin_url', $linkedin_url);
            $stmt->bindParam(':website_url', $website_url);
            $stmt->bindParam(':email_address', $email_address);
            $stmt->bindParam(':bg_image', $bg_image);
            $stmt->execute();

            header("Location: profiles.php?success=1");
            exit();
        } catch (PDOException $e) {
            $message = "Database error: " . htmlspecialchars($e->getMessage());
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
<link rel="stylesheet" href="css/modal-style.css">
    <div class="list">
        <h2>Profile</h2>
        <ul>
            <?php foreach ($records as $recordItem): ?>
                <li>
                    <a href="?profile_id=<?php echo htmlspecialchars($recordItem['profile_id']); ?>">
                        <?php echo htmlspecialchars($recordItem['profile_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="details">
<link rel="stylesheet" href="css/modal-style.css">
        <?php if ($record): ?>
<hr style="height:3px;border-width:0;color:white;background-color:blue">
            <h2>Edit Profile</h2>
        <?php else: ?>
<hr style="height:3px;border-width:0;color:white;background-color:blue">
            <h2>Add New Profile</h2>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <h2><p style="color: red; background-color: #dfa8bb;"><?php echo htmlspecialchars($message); ?></p></h2>
        <?php elseif (isset($_GET['success'])): ?>
            <h2><p style="color: green;background-color: #afcca2;">Record saved successfully!</p></h2>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <?php if ($record): ?>
                <input type="hidden" name="profile_id" value="<?php echo htmlspecialchars($record['profile_id']); ?>">
            <?php endif; ?>

            <label for="profile_name">Name:</label>
            <input type="text" id="profile_name" name="profile_name" value="<?php echo htmlspecialchars($record['profile_name'] ?? ''); ?>" required>

            <label for="profile_description">Profile Description:</label>
            <textarea id="profile_description" name="profile_description"><?php echo htmlspecialchars($record['profile_description'] ?? ''); ?></textarea>
        	    
	    <label for="github_url">Github URL:</label>
	    <textarea id="github_url" name="github_url"><?php echo htmlspecialchars($record['github_url'] ?? ''); ?></textarea>
	    
	    <label for="linkedin_url">Linkedin URL:</label>
	    <textarea id="linkedin_url" name="linkedin_url"><?php echo htmlspecialchars($record['linkedin_url'] ?? ''); ?></textarea>

	    <label for="website_url">Website URL:</label>
	    <textarea id="website_url" name="website_url"><?php echo htmlspecialchars($record['website_url'] ?? ''); ?></textarea>

            <label for="email_address">Email Address:</label>
            <textarea id="email_address" name="email_address"><?php echo htmlspecialchars($record['email_address'] ?? ''); ?></textarea>

            <label for="bg_image">Background Image:</label>
            <?php if (!empty($record['bg_image'])): ?>
                <p>Current Logo: <img src="images/<?php echo htmlspecialchars($record['bg_image']); ?>" alt="bg_image" style="max-width: 500px; max-height: 100px;"></p>
            <?php endif; ?>
            <input type="file" id="bg_image" name="bg_image" accept=".png, .jpg, .jpeg">

            <button type="submit">Save</button>
        </form>
    </div>

    //<script>
        //ClassicEditor
        //    .create(document.querySelector('#profile_description'))
        //    .then(editor => {
        //        editor.setData(<?php echo json_encode($record['profile_description'] ?? ''); ?>);

        //        document.querySelector('form').addEventListener('submit', () => {
        //            document.querySelector('#profile_description').value = editor.getData();
        //        });
        //    })
        //    .catch(error => {
        //        console.error(error);
        //    });
    //</script>
</body>
</html>
