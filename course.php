<?php
session_start();
define("PAGE_TITLE", "Courses");
require "assets/dbh/connector.php";
require "assets/includes/header.php";
include_once "assets/components/courseCard.php";
require "assets/components/navbar.php";

// Fetch courses from the database
$sql = "SELECT * FROM course ORDER BY c_id DESC";
$result = mysqli_query($conn, $sql);

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo PAGE_TITLE; ?></title>
</head>
<body>
    <div class="sbanner">
        <div class="overlay"></div>
        <h1>Courses</h1>
    </div>
    <br><br>
    <div class="main-content" id="main-content">
        <br><br>
        <div class="container">
            <div class="row">
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <?php
                    // Use the relative path stored in the database and pass the amount
                    courseCard($course['cimage'], $course['course_name'], $course['description'], $course['amount']);
                    ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
    require "assets/includes/footer.php";
    require "flask_chatbot/chatbot.php"; // Include the chatbot at the end of the page
    ?>

    
    <!-- JavaScript to check for 'message=success' in the URL and show alert -->
    <script>
        // Check if the URL contains 'message=success'
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('message') === 'success') {
            // Show success message
            alert('Course Application Submitted Successfully!');
            // Redirect to course.php after the alert
            window.location.href = 'course.php';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



