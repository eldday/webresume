<?php
require('fpdf.php');

// Database connection
require_once 'utilities/db_connection.php';
// Fetch job history
$query = "SELECT job_title, company_name, start_date, end_date, job_description FROM Job_history ORDER BY company_name, start_date DESC";
$statement = $pdo->query($query);
$job_history = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch skills
$query = "SELECT Skill_category_id, Skill_name FROM skills ORDER BY Skill_category_id";
$statement = $pdo->query($query);
$skills = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $skills[$row['skill_category_id']][] = $row['skill_name'];
}

class PDF extends FPDF
{
    function header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Resume', 0, 1, 'C');
    }

    function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function chapterTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $title, 0, 1, 'L');
    }

    function chapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, $body);
    }

    function skillsList($skills)
    {
        $this->SetFont('Arial', '', 12);
        foreach ($skills as $category => $items) {
            $this->chapterTitle($category);
            $this->chapterBody(implode(', ', $items));
        }
    }

    function jobHistory($job_history)
    {
        foreach ($job_history as $job) {
            $this->chapterTitle("Position: " . $job['job_title'] . " at " . $job['company_name']);
            $this->chapterBody("From: " . $job['start_date'] . " To: " . $job['end_date']);
            $this->chapterBody("Description: " . $job['job_description']);
        }
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();

// Add job history
$pdf->jobHistory($job_history);

// Add skills
$pdf->skillsList($skills);

// Output the PDF (force download)
$pdf->Output('I', 'resume_fpdf.pdf');
?>
