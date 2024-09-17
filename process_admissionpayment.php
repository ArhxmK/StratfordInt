<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "assets/dbh/connector.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = $_POST['admission_number'];
    $amount = $_POST['amount'];
    $mobile = $_POST['mobile'];
    $payment_receipt = $_FILES['payment_receipt'];

    // Validate form data
    if (empty($student_id) || empty($amount) || empty($mobile) || empty($payment_receipt['name'])) {
        $message = "All fields are required.";
    } else {
        // Define the target directory for uploads
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/StratfordInt/main_uploads/admissionpayment/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }
        $target_file = $target_dir . basename($payment_receipt["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($payment_receipt["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image. Detected type: " . $imageFileType;
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists: " . basename($payment_receipt["name"]);
            $uploadOk = 0;
        }

        // Check file size
        if ($payment_receipt["size"] > 500000) { // 500KB limit
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // If everything is okay, try to upload the file
        if ($uploadOk == 1 && move_uploaded_file($payment_receipt["tmp_name"], $target_file)) {
            // Store the relative path to the image in the database
            $relative_path = "main_uploads/admissionpayment/" . basename($payment_receipt["name"]);

            // Insert data into the admissionpayment table, now including the Mobile field
            $sql = "INSERT INTO admissionpayment (StudentId, Amount, Mobile, Receipt) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $student_id, $amount, $mobile, $relative_path);

            if (mysqli_stmt_execute($stmt)) {
                // Success: Show JavaScript alert and redirect to admissionpayment.php
                echo "<script>
                    alert('Admission Payment paid successfully.');
                    window.location.href = '/StratfordInt/admissionpayment.php';
                </script>";
                exit();
            } else {
                $message = "Error: " . mysqli_error($conn);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }

        // Close the connection
        mysqli_close($conn);
    }
}

// Show error message and redirect
if (!empty($message)) {
    echo "<script>
        alert('$message');
        window.location.href = '/StratfordInt/admissionpayment.php';
    </script>";
    exit();
}
?>
