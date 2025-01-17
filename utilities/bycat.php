<?php
require_once 'db_connection.php'; // Ensure db_connection.php sets $pdo

// Fetch category details and skills if a category ID is provided
$category_id = $_GET['category_id'] ?? null;
$category_name = '';
$skills = [];

if ($category_id && is_numeric($category_id)) {
    try {
        // Fetch category details
        $categoryStmt = $pdo->prepare("
            SELECT category_name
            FROM categories
            WHERE category_id = :category_id
        ");
        $categoryStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $categoryStmt->execute();
        $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $category_name = $category['category_name'];
        }

        // Fetch skills for the category
        $skillStmt = $pdo->prepare("
            SELECT Skill_name
            FROM skills
            WHERE Skill_category_id = :category_id
        ");
        $skillStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $skillStmt->execute();
        $skills = $skillStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching category details or skills: " . $e->getMessage());
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
    <title><?php echo htmlspecialchars($category_name); ?> - Skills</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            display: flex; /* Flexbox layout for logo and skills */
            align-items: flex-start;
            gap: 30px;
        }
        .category-info {
            text-align: center;
            width: 30%; /* Adjusted for better layout */
        }
        .skills-list {
            width: 70%; /* Adjusted for better layout */
        }
        .skills-list h2 {
            color: DodgerBlue;
            margin-bottom: 10px;
        }
        .skills-list ul {
            list-style-type: disc; /* Visible bullets */
            padding-left: 20px; /* Standard list indentation */
        }
        .skills-list li {
            background-clip: padding-box;
            background-color: rgba(0, 0, 0, 0.059);
            border: solid #ffffff1f 0.75pt; /* White border around each skill */
            border-width: 0 0 1px 0; /* White border only at the bottom */
            line-height: 1.38;
            margin-bottom: 5px;
            padding: 6pt 4pt;
            text-indent: 0;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Category Info -->

        <!-- Skills List -->
        <div class="skills-list">
            <h2><?php echo htmlspecialchars($category_name); ?></h2>
            <?php if (!empty($skills)): ?>
                <ul>
                    <?php foreach ($skills as $skill): ?>
                        <li><?php echo htmlspecialchars($skill['Skill_name']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No skills found for this category.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
