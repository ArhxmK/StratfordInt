<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "assets/dbh/connector.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $course_name = $_POST['course_name'];
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $grade = $_POST['grade'];  // Grade, ensure it is provided
    $mobile = $_POST['mobile'];  // Mobile number
    $amount = $_POST['amount'];
    $deposit_slip = $_FILES['deposit_slip'];

    // Validate form data
    if (empty($course_name) || empty($student_id) || empty($student_name) || empty($grade) || empty($mobile) || empty($amount) || empty($deposit_slip['name'])) {
        $message = "All fields are required, including grade.";
    } else {
        // Define the target directory for uploads
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/StratfordInt/main_uploads/course_slip/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
        }
        $target_file = $target_dir . basename($deposit_slip["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($deposit_slip["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image. Detected type: " . $imageFileType;
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists: " . basename($deposit_slip["name"]);
            $uploadOk = 0;
        }

        // Check file size
        if ($deposit_slip["size"] > 500000) { // 500KB limit
            $message = "Sorry, your file is too large. File size: " . $deposit_slip["size"] . " bytes.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed. You tried uploading a: " . $imageFileType;
            $uploadOk = 0;
        }

        // If everything is okay, try to upload the file
        if ($uploadOk == 1 && move_uploaded_file($deposit_slip["tmp_name"], $target_file)) {
            // Store the relative path to the image in the database
            $relative_path = "main_uploads/course_slip/" . basename($deposit_slip["name"]);

            // Insert data into the course_req table, including grade
            $sql = "INSERT INTO course_req (course_name, student_id, student_name, grade, mobile, amount, slip) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssss", $course_name, $student_id, $student_name, $grade, $mobile, $amount, $relative_path);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to course.php on success
                header("Location: /StratfordInt/course.php?message=success");
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

// Redirect with error message if something fails
header("Location: /StratfordInt/course.php?message=" . urlencode($message));
exit();
?>
