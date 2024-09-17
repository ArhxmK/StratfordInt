<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../admin/dbh/connector.php";

// Check if the Student ID is provided
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch the receipt file path from the database
    $query = "SELECT Receipt FROM admissionpayment WHERE StudentId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $receiptPath);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($receiptPath) {
        // Full path to the receipt file
        $file = "/Applications/XAMPP/xamppfiles/htdocs/StratfordInt/" . $receiptPath;

        if (file_exists($file)) {
            // Set headers to download the file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Content-Length: ' . filesize($file));

            // Read the file and send it to the browser
            readfile($file);
            exit();
        } else {
            echo "File not found!";
        }
    } else {
        echo "No receipt found for this student.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
