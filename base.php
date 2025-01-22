<?php
session_start();
if (isset($_SESSION['accessLevel'])) {
echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
}
// Define session timeout duration (e.g., 30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check if "lastActivity" is set in the session
if (isset($_SESSION['lastActivity'])) {
    // Calculate the session's lifetime
    $elapsedTime = time() - $_SESSION['lastActivity'];

    // If the session has expired
    if ($elapsedTime > $timeoutDuration) {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        exit();
    }
}
require_once 'db_connection.php'; // Ensure db_connection.php sets $pdo

// Fetch profile information
try {
    $stmt = $pdo->prepare("SELECT * from profile");
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        throw new Exception("Profile information not found.");
    }
} catch (PDOException $e) {
    die("Error fetching profile: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}


// Fetch all categories and their associated skills
try {
    $stmt = $pdo->prepare("
        SELECT c.category_name, s.Skill_name
        FROM categories c
        LEFT JOIN skills s ON c.category_id = s.Skill_category_id
        ORDER BY c.category_name, s.Skill_name
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group skills by category
    $categories = [];
    foreach ($data as $row) {
        $categories[$row['category_name']][] = $row['Skill_name'];
    }
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills by Category</title>
	    <style>
	header {
	    color: black;
	    background-color: black;
	    padding: 5px;
	    text-align: left;
	    font-size: 1.5em;
	    }
	nav {
	    background-color: black;
	    padding: 0px;
	    }
	body {
		    
    	    font-family: 'Source Code Pro', Arial;
            //font-family: , sans-serif;
            margin: 0;
            padding: 0;
	    }
		/* Header Section */
	header {
	    position: relative;
	    height: 230px; /* Adjust as needed */
	    background-image: url('images/shoes.png'); /* Replace with your image file */
	    background-size: cover;
	    background-position: left;
	    display: flex;
	    align-items: left;
	    justify-content: center;
	    color: white;
	    overflow: hidden;
	}

	header::before {
	    content: '';
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    background-color: rgba(0, 0, 0, 0.5); /* Transparent gray overlay */
	    z-index: 1;
	}

	header .content {
	    position: left;
//	     left: 10;
	    z-index: 2;
	    text-align: left;
	}
	header .link {
	    top: 2px;
	    bottom: 10;
	}
	header h1 {
	    margin: 2px;
	    font-size: 2rem;
	}

/* Main Content Layout */
	.container {
	    display: flex;
	    flex-wrap: wrap; /* Ensure the columns wrap to next line if needed */
	    justify-content: center; /* Center columns horizontally */
	    gap: 20px; /* Space between columns */
	    margin: 10px auto; /* Center the container with margin */
	    max-width: 1200px; /* Limit max-width for the container */
	}

/* Skill List */
	.Skill-list {
	    flex: 1 1 45%; /* Adjust the size of the columns (45% is a smaller size) */
	    margin-bottom: 15px;
	    max-width: 500px; /* Set a max-width to prevent too large columns */
	}

	/* Skill Items */
	.Skill-item {
	    font-family: 'Source Code Pro', Arial;
	    align-contentbackground-clip: padding-box;
	    background-color: rgba(0,0,0,0.0590000004);
	    background-clip: padding-box;
	    background-color: rgba(0, 0, 0, 0.0590000004);
	    border-bottom: none;
	    border-left: solid #ffffff1f 0.75pt;
	    border-right: solid #ffffff1f 0.75pt;
	    border-top: none;
	    line-height: 1.38;
	    margin-bottom: 15px;
	    padding-left:  4.0pt;
            padding-right: 4.0pt;
            padding-top:   4.0pt;   
	    padding-bottom: 5.0pt; 
	   //padding: 6px 4px;
	    text-indent: 0;
	    list-style-type: square; /* Bullet points */
	}

	.Skill-item p {
	   margin: 0;
	}

	.Skill-list {
      	   font-family: 'Source Code Pro', Arial;
           align-contentbackground-clip: padding-box;
           background-color: rgba(0,0,0,0.0590000004);
	   flex: 1 1 45%; /* Adjust the size of the columns (45% is a smaller size) */
	   margin-bottom: 15px;
           max-width: 500px; /* Set a max-width to prevent too large columns */
	}
	/* Style for each skill */
	.Skill-item ul {
    	    font-family: 'Source Code Pro', Arial;
	    margin-left: 20px;
	}

	.Skill-item li {
	    list-style-type: square;
	    margin-bottom: 20px;
	    color: #333;
	}

	.Skill-item h2 {
	    font-size: 18px;
	    margin-bottom: 5px;
	    color: #444;
	}
        .content  {
	    margin-left: auto;
	    margin-right: auto;
 	    font-family: Roboto, Arial;
  	    font-weight: 700;
  	    vertical-align: baseline;
            font-variant: normal;
            font-weight: 400;
//            background-color: rgba(0, 0, 0, 0.059);
            font-size: 20px;
 	    line-height: 0.6;
            margin-top: 20pt;
	    margin-bottom: 0px;
	    padding-left:  4.0pt;
            padding-right: 4.0pt;
	    text-align: left;
	}
	.container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
           max-width: 1200px; 
       }
        .column {
            flex: 1; /* Two equal-width columns */
            min-width: 100px;
            padding: 20px;
            border: 0px solid #ddd;
            //background-color: #f9f9f9;
            border-radius: 8px;
        }
        .category {
            margin-bottom: 0px;
        }
        .category h2 {
            color: #1155cc;
    	    font-family: 'Source Code Pro', Arial;
  	    font-variant: normal;
  	    font-weight: 700;
	    color: DodgerBlue;
            background-color: rgba(0, 0, 0, 0.059);
	    font-size: 20px;
            margin-bottom: 0px;
            padding-left:  4.0pt;
            padding-right: 4.0pt;
            padding-top:   4.0pt;
	    padding-bottom: 2.0pt;
	}
        .Skill-list {
            font-family: 'Source Code Pro', Arial;
	    flex: 1 1 45%; /* Adjust the size of the columns (45% is a smaller size) */
	    margin-bottom: 2px;
	    max-width: 500px; /* Set a max-width to prevent too large columns */
	}
	.skills-list ul {
    		    font-family: 'Source Code Pro', Arial;
	            list-style-type: square;
	            padding-left: 30px;
	        }
        .skills-list li {
		  font-family: 'Source Code Pro', Arial;
		  align-contentbackground-clip: padding-box;
		  background-color: rgba(0,0,0,0.0590000004);
		  border-bottom: none;
		  border-left: solid #ffffff1f 0.75pt;
		  border-right: solid #ffffff1f 0.75pt;
		  border-top: none;
		  line-height: 1.38;
		  margin-bottom: 4pt;
		  margin-left: 0.0pt;
		  margin-top: 	4.0pt;
		  padding-bottom: 6.0pt;
		  padding-left:  4.0pt;
		  padding-right: 4.0pt;
		  padding-top:   0.0pt;
		  text-indent: 0.0pt;
		}

		.link {
			display: inline-block;
		  	vertical-align: middle;
			border-radius: 50%;
			width: 32px;
		  	height: 32px;
		  	margin: 6px;
		  	background-color: rgb(95, 99, 104);
		  	background-image: linear-gradient(rgb(95, 99, 104), rgb(95, 99, 104));
			}
	/* Responsive Layout */
	@media (max-width: 768px) {
	    .Skill-list {
	        flex: 1 1 100%; /* On smaller screens, skills take full width */
	        max-width: 100%; /* Remove max-width on small screens */
	    }
	}

    </style>
</head>
<body>
<header>
    <div class="content">
	<br><br><br>
        <h1><?php echo htmlspecialchars($profile['profile_name']); ?></h1>
	<p><?php echo htmlspecialchars($profile['profile_description']); ?></p><br>
        <a class="link" href="<?php echo htmlspecialchars($profile['linkedin_url']); ?>" target="_blank"><img src="images/linkedin_white_28dp.png" width="32" height="32"></a>
        <a class="link" href="<?php echo htmlspecialchars($profile['github_url']); ?>" target="_blank"><img src="images/github_white_28dp.png" width="32" height="32"></a>
        <a class="link" href="mailto:<?php echo htmlspecialchars($profile['email']); ?>" target="_blank"><img src="images/email_white_28dp.png" width="32" height="32"></a>
    </div>
</header>
    <div class="container">
        <?php 
        // Split categories into two columns
        $categoriesChunks = array_chunk($categories, ceil(count($categories) / 2), true);
        foreach ($categoriesChunks as $column): ?>
            <div class="column">
                <?php foreach ($column as $categoryName => $skills): ?>
                    <div class="category">
                        <h2><?php echo htmlspecialchars($categoryName); ?></h2>
                        <div class="skills-list" style="font-family: 'Source Code Pro', Arial; font-variant: normal; font-weight: 400;">
                            <ul>
                                <?php foreach ($skills as $skill): ?>
                                    <li><?php echo htmlspecialchars($skill); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
