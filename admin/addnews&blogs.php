<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("PAGE_TITLE", "Add News");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php";

$message = "";
$newsId = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $subject = $_POST['subject'];
    $article = $_POST['news'];
    $image = $_FILES['image'];

    // Validate form data
    if (empty($subject) || empty($article) || empty($image['name'])) {
        $message = "All fields are required.";
    } else {
        // Generate the next b_id
        $sql = "SELECT b_id FROM blogs ORDER BY b_id DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $lastId = $row['b_id'];
            $num = intval(substr($lastId, 1)) + 1;
            $newsId = 'b' . str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $newsId = 'b001';
        }

        // Define the target directory for uploads
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/StratfordInt/uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($image["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $message = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($image["size"] > 500000) {
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Store relative path to the database
                $relative_path = "uploads/" . basename($image["name"]);
                
                // Insert data into the database
                $sql = "INSERT INTO blogs (b_id, subject, article, image_path) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $newsId, $subject, $article, $relative_path);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "News added successfully.";
                } else {
                    $message = "Error: " . mysqli_error($conn);
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                $message = "Sorry, there was an error uploading your file.";
                $message .= " Target directory: " . $target_dir;
                $message .= " Target file: " . $target_file;
                $message .= " Is writable: " . (is_writable($target_dir) ? "Yes" : "No");
                $message .= " File upload error code: " . $image["error"];
            }
        }

        // Close the connection
        mysqli_close($conn);
    }
}

require "../admin/includes/sidebar.php";
?>

<!-- Sidebar Toggle Button -->
<div class="wrapper">
    <button id="sidebarToggle">&#9776;</button>
</div>

<div class="main-content" id="main-content">
    <div class="form-container">
        <h1>Add News</h1>
        <div id="message" class="message-box"><?php echo htmlspecialchars($message); ?></div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="news">News Article</label><br>
                <textarea id="news" name="news" rows="4" cols="50" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Add News">
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Sidebar Toggle -->
<script>
    window.onload = function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        let isSidebarCollapsed = sidebar.classList.contains('collapsed');

        sidebarToggle.addEventListener('click', function(event) {
            event.stopPropagation();

            if (isSidebarCollapsed) {
                sidebar.classList.remove('collapsed');
                content.classList.remove('collapsed');
                sidebarToggle.style.color = 'white';
            } else {
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
                sidebarToggle.style.color = 'black';
            }

            isSidebarCollapsed = !isSidebarCollapsed;
        });
    };
</script>

<!-- Styling for Sidebar and Main Content -->
<style>
    .main-content {
        padding: 20px;
        background-color: #f9f9f9;
        transition: all 0.3s ease;
        display: flex;  /* Ensures the form container stays centered */
        justify-content: center; /* Centers the form */
        align-items: center;
        min-height: 100vh; /* Centers vertically */
    }

    #main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
        display: flex; /* Ensures the form stays centered when the sidebar is expanded */
        justify-content: center;
    }

    #main-content.collapsed {
        margin-left: 0;
        width: 100%;
        display: flex; /* Ensures the form stays centered when the sidebar is collapsed */
        justify-content: center;
    }

    /* Sidebar Styling */
    #sidebar {
        width: 250px;
        background: #061355;
        position: fixed;
        height: 100%;
        left: 0;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    #sidebar.collapsed {
        width: 0;
        left: -250px;
    }

    /* Sidebar behavior for smaller screens */
    @media (max-width: 768px) {
        #main-content {
            margin-left: 0;
            width: 100%;
        }

        #sidebar {
            width: 70%;  /* Sidebar covers 70% of the screen */
        }

        #sidebar.collapsed {
            left: -70%;  /* Sidebar slides off-screen by 70% */
        }
    }

    #sidebarToggle {
        position: fixed;
        top: 15px;
        left: 15px;
        background: transparent;
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        z-index: 1001;
        transition: color 0.3s ease;
    }

    /* Form Container Styling */
    .form-container {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group input[type="file"],
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    .form-group input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #f39c12;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .form-group input[type="submit"]:hover {
        background-color: #d3870e;
    }

    .message-box {
        text-align: center;
        margin-bottom: 20px;
        color: red;
    }

    @media (max-width: 600px) {
        .form-container {
            padding: 20px;
        }

        h1 {
            font-size: 20px;
        }

        .form-group input[type="submit"] {
            font-size: 14px;
        }
    }
</style>

<script src="js/script.js?v=1" defer></script>
</body>
</html>
