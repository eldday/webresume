<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

// Start the session to handle user selection

// If the form is submitted, store the selected option
if (isset($_POST['pdf_generator'])) {
    $_SESSION['pdf_generator'] = $_POST['pdf_generator'];
    header('Location: generate_resume.php'); // Redirect to the resume generation page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose PDF Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFF;
        }
        .container {
            width: 50%;
            margin: 100px auto;
            padding: 20px;
            background-color: #c3d0d9;
            border-radius: 10px;
      	    box-shadow: 0px 0px 10px rgba(0, 0, 0, 1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 18px;
            margin-right: 10px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Your Resume</h1>
        <form action="" method="POST">
            <label for="pdf_generator">Using PDF Generator:</label>
            <select name="pdf_generator" id="pdf_generator">
                <option value="dompdf" <?php echo isset($_SESSION['pdf_generator']) && $_SESSION['pdf_generator'] == 'dompdf' ? 'selected' : ''; ?>>DOMPDF</option>
            </select>
            <button type="submit">Generate Resume</button>
        </form>
    </div>
</body>
</html>