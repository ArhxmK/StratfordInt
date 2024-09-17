<?php
session_start(); // Start the session to store session variables
define("PAGE_TITLE", "Home");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php"; // Include the database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hardcoded admin username and password for demonstration purposes
    $adminUsername = 'STRAT_FORD';
    $adminPassword = 'LFM6B0RR';

    // Get user input
    $enteredUsertype = $_POST['usertype'];
    $enteredUsername = $_POST['username'];
    $enteredPassword = $_POST['password'];

    // Debug: Check if inputs are received
    if (empty($enteredUsername) || empty($enteredPassword)) {
        $errorMessage = "Username and Password are required.";
    } else {
        if ($enteredUsertype == 'admin') {
            if ($enteredUsername == $adminUsername && $enteredPassword == $adminPassword) {
                // Redirect to admin home page
                header("Location: ../admin/adminhome.php");
                exit();
            } else {
                $errorMessage = "Incorrect admin username or password.";
            }
        } else if ($enteredUsertype == 'user') {
            // Prepare the SQL statement to check user credentials
            $sql = "SELECT * FROM staff WHERE staffname = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $enteredUsername);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    // Check account status
                    if ($row['account_status'] == 0) {
                        $errorMessage = "Account is disabled.";
                    } else if (password_verify($enteredPassword, $row['password'])) {
                        // Password is correct, set session and redirect to staff home page
                        $_SESSION['staff_id'] = $row['staff_id']; // Set staff_id in session
                        $_SESSION['staff_name'] = $row['staffname']; // Set staff name in session
                        
                        header("Location: ../admin/staffhome.php");
                        exit();
                    } else {
                        $errorMessage = "Incorrect username, password, or usertype.";
                    }
                } else {
                    $errorMessage = "Incorrect username, password, or usertype.";
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                $errorMessage = "Database query failed.";
            }
        } else {
            $errorMessage = "Incorrect username, password, or usertype.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo PAGE_TITLE; ?></title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Full white background */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .login-form {
            background-color: transparent; /* Removed background color */
            padding: 20px;
        }

        .login-form .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-form h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .user-type-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .user-type-container div {
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin: 0 10px;
        }

        .user-type-container div.active {
            background-color: #002147;
            color: #fff;
        }

        .user-type-container div.active i {
            color: #fff !important; /* Ensure the icon changes to white */
            -webkit-text-stroke: 0; /* Remove the stroke effect when active */
        }

        .user-type-container i {
            -webkit-text-stroke: 1px #002147; /* Stroke effect for outline */
            color: transparent; /* Hide original fill */
            font-size: 50px; /* Adjust icon size */
            margin-bottom: 10px;
            transition: color 0.3s ease, -webkit-text-stroke 0.3s ease;
        }

        .input-container {
            position: relative;
            margin-bottom: 15px;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
        }

        .input-container input {
            width: 100%;
            padding: 10px 10px 10px 40px; /* Add padding for icon */
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #002147;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #003366;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <form class="login-form" method="POST" action="">
        <img src="../admin/img/logo.png" alt="Logo" class="logo"> <!-- Add your logo image URL here -->
        <h2>STRATFORD INTERNATIONAL COLLEGE</h2> <!-- Bold heading under the logo -->
        
        <div class="user-type-container">
            <div id="adminIcon" class="user-type active" data-type="admin">
                <i class="fas fa-user"></i> <!-- Mimic outline Admin Icon -->
                <p>Admin</p>
            </div>
            <div id="teacherIcon" class="user-type" data-type="user">
                <i class="fas fa-users"></i> <!-- Mimic outline Teacher Icon -->
                <p>Staff</p>
            </div>
        </div>

        <input type="hidden" id="usertype" name="usertype" value="admin">
        
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        
        <button type="submit">Login</button>
        
        <?php
        if (!empty($errorMessage)) {
            echo '<p class="error-message">' . htmlspecialchars($errorMessage) . '</p>';
        }
        ?>
    </form>
</div>

<script>
    const adminIcon = document.getElementById('adminIcon');
    const teacherIcon = document.getElementById('teacherIcon');
    const usertypeInput = document.getElementById('usertype');

    adminIcon.addEventListener('click', function() {
        usertypeInput.value = 'admin';
        adminIcon.classList.add('active');
        teacherIcon.classList.remove('active');
    });

    teacherIcon.addEventListener('click', function() {
        usertypeInput.value = 'user';
        teacherIcon.classList.add('active');
        adminIcon.classList.remove('active');
    });
</script>

</body>
</html>
