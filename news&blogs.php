<?php
session_start();
define("PAGE_TITLE", "News & Blogs");
require "assets/dbh/connector.php";
require "assets/includes/header.php";
include_once "assets/components/newsCard.php";
require "assets/components/navbar.php";

// Fetch news from the database
$sql = "SELECT * FROM blogs ORDER BY b_id DESC";
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
        <h1>News & Blogs</h1> <!-- Example: About Us, Contact Us -->
    </div>
    <br><br>
    <div class="main-content" id="main-content">
        <br><br>
        <div class="container">
            <div class="row">
                <?php while ($news = mysqli_fetch_assoc($result)): ?>
                    <?php
                    // Use the relative path stored in the database
                    newsCard($news['image_path'], $news['subject'], $news['article'], $news['b_id']);
                    ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
require "assets/includes/footer.php";
require "flask_chatbot/chatbot.php"; // Include the chatbot at the end of the page
?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
