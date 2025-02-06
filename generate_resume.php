<?php
// Start the session to access the selected PDF generator
session_start();

// Check if the user has selected a generator
if (!isset($_SESSION['pdf_generator'])) {
    die("Please select a PDF generator first.");
}

// Include the appropriate PHP file based on the selected generator
if ($_SESSION['pdf_generator'] == 'fpdf') {
    require('generate_resume_fpdf.php');
} elseif ($_SESSION['pdf_generator'] == 'dompdf') {
    require('generate_resume_dompdf.php');
} elseif ($_SESSION['pdf_generator'] == 'tcpdf') {
    require('generate_resume_tcpdf.php');
} else {
    die("Invalid PDF generator selection.");
}
?>
