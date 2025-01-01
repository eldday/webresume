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
$name = $email = "";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";

    if (!empty($name) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Job_history (company_name, company_id, job_description, start_date, end_date, job_title) VALUES (:company_name, :company_id, :job_description, :start_date, :end_date, :job_title)");
            $stmt->bindParam(":company_name", $company_name);
	    $stmt->bindParam(":company_id", $company_id);
            $stmt->bindParam(":job_desciption", $job_description);
           $stmt->bindParam(":job_title", $job_title);
           $stmt->bindParam(":start_date", $start_date);
           $stmt->bindParam(":end_date", $end_date);
            $stmt->execute();
            $message = "Data inserted successfully!";
        } catch (PDOException $e) {
            $message = "Error inserting data: " . $e->getMessage();
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
    <title>Insert Data</title>
</head>
<body>
    <h1>Insert Data into Database</h1>
    <?php if (!empty($message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="company_name">Company Name:</label><br>
        <input type="text" id="company_name" name="company_name" required><br><br>

       <label for="company_id">Company ID:</label><br>
        <input type="number" id="company_id" name="company_id" required><br><br>

        <label for="job_description">Job Description:</label><br>
        <input type="text" id="job_desctiption" name="job_description" required><br><br>

	<label for="job_title">Job Title:</label><br>
        <input type="text" id="job_title" name="job_title" required><br><br>

	<label for="start_date">Start Date:</label><br>
	<input type="date" id="start_date" name="start_date" required><br><br>

	<label for="end_date">End Date:</label><br>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <button type="submit">Insert</button>
    </form>
</body>
</html>
