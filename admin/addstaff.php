<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define("PAGE_TITLE", "Add Staff");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php";

$message = "";
$staffId = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $staffId = $_POST['staffId'];
    $staffName = $_POST['staffName'];
    $role = $_POST['role'];
    $age = $_POST['age'];
    $nicNo = $_POST['nicNo'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Handle image upload
    $targetDir = "../main_uploads/staff_profiles/";
    $profileImage = $_FILES['profileImage']['name'];
    $targetFile = $targetDir . basename($profileImage);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate form data
    if (empty($staffName) || empty($role) || empty($age) || empty($nicNo) || empty($password) || empty($confirmPassword) || empty($profileImage)) {
        $message = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $message = "Invalid file type for image. Only JPG, JPEG, PNG, and GIF are allowed.";
    } elseif (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
        $message = "Failed to upload image.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        $sql = "INSERT INTO staff (staff_id, staffname, role, age, nic, password, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $staffId, $staffName, $role, $age, $nicNo, $hashedPassword, $targetFile);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Staff added successfully.";

            // Generate the next Staff Id
            $sql = "SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $lastId = $row['staff_id'];
                $num = intval(substr($lastId, 1)) + 1;
                $staffId = 'S' . str_pad($num, 3, '0', STR_PAD_LEFT);
            } else {
                $staffId = 'S001';
            }
        } else {
            $message = "Error: " . mysqli_error($conn);
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    // Generate the next Staff Id for the form
    $sql = "SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $lastId = $row['staff_id'];
        $num = intval(substr($lastId, 1)) + 1;
        $staffId = 'S' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $staffId = 'S001';
    }
}

require "../admin/includes/sidebar.php";
?>

<!-- Sidebar Toggle Button -->
<div class="wrapper">
    <button id="sidebarToggle">&#9776;</button>
</div>
<br><br>
<div class="main-content" id="main-content">
    <div class="form-container">
        <h1>Add Staff</h1>
        <div id="message" class="message-box"><?php echo htmlspecialchars($message); ?></div>
        <form action="addstaff.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="staffId">Staff Id</label>
                <input type="text" id="staffId" name="staffId" value="<?php echo htmlspecialchars($staffId); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="staffName">Staff Name</label>
                <input type="text" id="staffName" name="staffName" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="nicNo">NIC No</label>
                <input type="text" id="nicNo" name="nicNo" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="profileImage">Profile Image</label>
                <input type="file" id="profileImage" name="profileImage" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Add Staff">
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
        max-width: 500px;
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
    .form-group input[type="number"],
    .form-group input[type="password"],
    .form-group input[type="file"] {
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
