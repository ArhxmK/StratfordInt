<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../admin/dbh/connector.php";

// Check if the request ID is provided
if (isset($_GET['id'])) {
    $req_id = $_GET['id'];

    // Fetch the slip image path from the database
    $query = "SELECT slip FROM course_req WHERE req_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $req_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $slipPath);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($slipPath) {
        $file = "/Applications/XAMPP/xamppfiles/htdocs/StratfordInt/" . $slipPath;  // Full path to the receipt file

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
        echo "No receipt found for this request.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
