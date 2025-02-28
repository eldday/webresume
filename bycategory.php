<?php

require_once 'utilities/db_connection.php';
// Fetch category details and Skills if a category ID is provided
$category_id = $_GET['category_id'] ?? null;
$category_name = '';
$skills = [];

if ($category_id && is_numeric($category_id)) {
    try {
        // Query to fetch category details (name and logo)
        $categoryStmt = $pdo->prepare("
            SELECT category_name
            FROM categories
            WHERE category_id = :category_id
        ");
        $categoryStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $categoryStmt->execute();
        $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);
        
        // If category exists, set the category name
        if ($category) {
            $category_name = $category['category_name'];
        }

        // Query to fetch Skills related to the category, ordered by end_date descending
        $SkillStmt = $pdo->prepare("
            SELECT Skill_name, Skill_category_id
            FROM skills
            WHERE skill_category_id = :category_id;
        ");
        $SkillStmt->bindParam(':category_id', $skill_category_id, PDO::PARAM_INT);
        $SkillStmt->execute();
        $Skills = $SkillStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching category details or Skills: " . $e->getMessage());
    }
} else {
    die("Invalid or missing category ID.");
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
	    margin-top: 20px;
            margin: 20px;
        }
        .container {
            display: flex; /* Flexbox container */
            align-items: flex-start;
        }
        .category-info {
            margin-right: 30px; /* Space between logo and Skills */
            text-align: center;
        }
        .category-logo {
            width: 100%;  /* Increased size */
            height: 100%; /* Increased size */
            margin-bottom: 10px;
        }
        .Skill-list {
            flex-grow: 1; /* Jobs take up the remaining space */
        }
        .Skill-item {
            border: 0px solid #FFF;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .Skill-item h2 {
            margin: 0;
            font-size: 18px;
        }
        .Skill-item p {
            margin: 5px 0;
        }
        .Skill-description ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .Skill-description li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<br><br><br><br>
    <div class="container">
        </div>

        <!-- Job listings column -->
        <div class="Skill-list">
            <?php if (!empty($Skills)): ?>
                <?php foreach ($Skills as $Skill): ?>
                    <div class="category-item">
                        <h2 style="color:DodgerBlue;"><?php echo htmlspecialchars($Skill['category_name']); ?></h2>
                       <div style="display: flex; justify-content: space-around"><p><strong><br></strong></p> <p><strong>From:</strong> <?php echo htmlspecialchars($Skill['start_date']);?> <strong> To: </strong><?php echo htmlspecialchars($Skill['end_date']); ?></p></div>
                        <div class="Skill-name">
                            <?php echo $Skill['Skill_name']; // Output raw HTML content ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Skills found for this category.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
