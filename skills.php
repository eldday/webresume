<?php
session_start();
// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";

   exit();
}


require_once 'utilities/db_connection.php';
// Fetch all skills
$skills = [];
try {
    $stmt = $pdo->query("SELECT Skill_id, Skill_name, Skill_category_id FROM skills");
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching skills: " . $e->getMessage());
}

// Fetch all categories
$categories = [];
try {
    $stmt = $pdo->query("SELECT category_id, category_name FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}

// Fetch a specific skill if requested
$selectedSkill = null;
if (isset($_GET['Skill_id']) && is_numeric($_GET['Skill_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM skills WHERE Skill_id = :Skill_id");
        $stmt->bindParam(':Skill_id', $_GET['Skill_id'], PDO::PARAM_INT);
        $stmt->execute();
        $selectedSkill = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching specific skill: " . $e->getMessage());
    }
}

// Handle form submissions
$message = "";

// Update a Skill record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Skill_id'])) {
    $Skill_id = $_POST['Skill_id'];
    $Skill_category_id = $_POST['Skill_category_id'];
    $Skill_name = $_POST['Skill_name'];

    if ($Skill_id && $Skill_category_id && $Skill_name) {
        try {
            $stmt = $pdo->prepare("UPDATE skills SET Skill_category_id = :Skill_category_id, Skill_name = :Skill_name WHERE Skill_id = :Skill_id");
            $stmt->bindParam(':Skill_id', $Skill_id, PDO::PARAM_INT);
            $stmt->bindParam(':Skill_category_id', $Skill_category_id, PDO::PARAM_INT);
            $stmt->bindParam(':Skill_name', $Skill_name);
            $stmt->execute();
            $message = "Skill updated successfully!";
        } catch (PDOException $e) {
            $message = "Error updating skill: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

// Add a new Skill
if (isset($_POST['add_skill'])) {
    $Skill_category_id = $_POST['Skill_category_id'];
    $Skill_name = $_POST['Skill_name'];

    if ($Skill_category_id && $Skill_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO skills (Skill_category_id, Skill_name) VALUES (:Skill_category_id, :Skill_name)");
            $stmt->bindParam(':Skill_category_id', $Skill_category_id, PDO::PARAM_INT);
            $stmt->bindParam(':Skill_name', $Skill_name);
            $stmt->execute();
            $message = "Skill added successfully!";
        } catch (PDOException $e) {
            $message = "Error adding skill: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

// Add a new Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];

    if ($category_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (:category_name)");
            $stmt->bindParam(':category_name', $category_name);
            $stmt->execute();
            $message = "Category added successfully!";
        } catch (PDOException $e) {
            $message = "Error adding category: " . $e->getMessage();
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
    <title>Manage Skills and Categories</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
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
<link rel="stylesheet" href="css/modal-style.css">
    <h2>Skills</h2>
    <ul>
        <?php foreach ($skills as $skill): ?>
            <li>
                <a href="?Skill_id=<?= htmlspecialchars($skill['Skill_id']); ?>">
                    <?= htmlspecialchars($skill['Skill_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="details">
 <hr style="height:3px;border-width:0;color:white;background-color:blue">
<link rel="stylesheet" href="css/modal-style.css">
   <?php if ($message): ?>
        <h2><p style="color:green;background-color: #afcca2;"><?= htmlspecialchars($message); ?></p></h2>
    <?php endif; ?>
    <h2><?= $selectedSkill ? 'Edit Skill' : 'Add New Skill' ?></h2>
    <form method="POST">
        <?php if ($selectedSkill): ?>
            <input type="hidden" name="Skill_id" value="<?= htmlspecialchars($selectedSkill['Skill_id']); ?>">
        <?php endif; ?>

        <label for="Skill_category_id">Category:</label>
        <select id="Skill_category_id" name="Skill_category_id" required>
            <option value="">Select a category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['category_id']); ?>"
                    <?= $selectedSkill && $selectedSkill['Skill_category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
<br><br>
	<label for="Skill_name">Skill Name:</label>
	<textarea id="Skill_name" name="Skill_name" rows="3" cols="40" required><?= $selectedSkill ? htmlspecialchars($selectedSkill['Skill_name']) : '' ?></textarea>

        <button type="submit" name="<?= $selectedSkill ? 'update_skill' : 'add_skill' ?>">
            <?= $selectedSkill ? 'Update Skill' : 'Add Skill' ?>
        </button>
    </form>
 <hr style="height:3px;border-width:0;color:white;background-color:blue">
    <h2>Add New Category</h2>
    <form method="POST">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required>
        <button type="submit" name="add_category">Add Category</button>
    </form>
</div>
//    <script>
 //       ClassicEditor
  //          .create(document.querySelector('#Skill_name'))
   //         .catch(error => {
    //            console.error(error);
     //       });
    //</script>
</body>
</html>
