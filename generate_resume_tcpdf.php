<?php
require_once('vendor/autoload.php');

use TCPDF;

// Database connection
$host = 'localhost';
$dbname = 'resume';
$username = 'pday';
$password = 'quality';

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch job history
$query = "SELECT title, company, start_date, end_date, description FROM jobs ORDER BY company, start_date DESC";
$statement = $pdo->query($query);
$job_history = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch skills
$query = "SELECT category, skill FROM skills ORDER BY category";
$statement = $pdo->query($query);
$skills = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $skills[$row['category']][] = $row['skill'];
}

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Job History
$pdf->SetFont('helvetica', 'B', 12);
foreach ($job_history as $job) {
    $pdf->Cell(0, 10, 'Position: ' . $job['title'] . ' at ' . $job['company'], 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'From: ' . $job['start_date'] . ' To: ' . $job['end_date'], 0, 1);
    $pdf->MultiCell(0, 10, 'Description: ' . $job['description']);
}

// Skills
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Skills', 0, 1);
$pdf->SetFont('helvetica', '', 12);
foreach ($skills as $category => $items) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, $category . ':', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, implode(', ', $items));
}

// Output the PDF (force download)
$pdf->Output('I', 'resume_tcpdf.pdf');
?>
