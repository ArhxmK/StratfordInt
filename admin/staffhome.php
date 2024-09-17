<?php
session_start();
define("PAGE_TITLE", "Home");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php";

// Ensure the user is logged in and the staff_id session is set
if (!isset($_SESSION['staff_id'])) {
    echo "Please log in.";
    exit();
}

// Get staff details using the session
$staffId = $_SESSION['staff_id'];
$staffName = $_SESSION['staff_name']; // You can now use this to display the staff name

// Fetch other staff details if needed
$sql = "SELECT profile_image FROM staff WHERE staff_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $staffId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$staff = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if the profile image is set
$profileImage = $staff['profile_image'] ?? ''; // Empty string if no image path found

// If no profile image is found, use a default one
$profileImage = $profileImage ?: '../assets/default_profile.png';

require "../admin/includes/sidebarstaff.php";
?>

<div class="wrapper">
    <!-- Toggle Icon Outside Sidebar -->
    <button id="sidebarToggle">&#9776;</button>

   
    
     <!-- Content -->
     <div id="content">
     <br><br>
     <h1>Welcome, <?php echo htmlspecialchars($staff['staffname']); ?></h1>
    <br>
            <div class="dashboard">
                <div class="card card-1">
                    <h4>Total Employees</h4>
                    <p>100</p>
                </div>
                <div class="card card-2">
                    <h4>Total Students</h4>
                    <p>500</p>
                </div>
                <div class="card card-3">
                    <h4>Total Admissions</h4>
                    <p>50</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<br><br>


<style>
  /* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
}

.wrapper {
    display: flex;
}

/* Main content with transition when sidebar is collapsed */
.main-content {
    padding: 20px;
    background-color: #f9f9f9;
    margin-left: 250px; /* Initially, leave space for the sidebar */
    transition: margin-left 0.3s ease; /* Smooth transition */
    width: calc(100% - 250px); /* Content takes up the remaining width */
}

/* Sidebar */
#sidebar {
    width: 250px;
    background: #061355;
    position: fixed;
    height: 100%;
    left: 0;
    transition: all 0.3s ease;
    z-index: 1000;
}

/* Sidebar in collapsed state */
#sidebar.collapsed {
    left: -250px;
}

/* Sidebar Profile Section */
.sidebar-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 0;
    background-color: #061355;
}

.sidebar-profile img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 2px solid #fff;
    margin-bottom: 10px;
    object-fit: cover;
}

.sidebar-profile h4 {
    color: white;
    font-size: 18px;
    margin: 0;
    text-align: center;
}

/* Sidebar Menu */
ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    padding: 15px 20px;
}

ul li a {
    color: white;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s ease;
}

ul li a:hover {
    background-color: #007bff;
}

ul li a.active {
    background-color: #007bff;
    border-radius: 4px;
}

/* Content adjustment when sidebar is collapsed */
#main-content.collapsed {
    margin-left: 0;
    width: 100%;
}

/* Toggle Button Styles */
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

/* Make sure sidebar is visible on desktop without JavaScript */
@media (min-width: 769px) {
    #sidebar {
        left: 0; /* Sidebar is always visible on large screens */
    }
}

/* Responsive design for mobile/smaller screens */
@media (max-width: 768px) {
    #sidebar {
        width: 70%;
        left: -100%; /* Sidebar initially off-screen */
        transition: left 0.3s ease; /* Smooth transition for showing/hiding */
    }

    #sidebar.collapsed {
        left: 0; /* Sidebar is shown when not collapsed */
    }

    #main-content {
        margin-left: 0;
        width: 100%;
    }

    #sidebarToggle {
        display: block; /* Toggle button visible on mobile */
    }
}

/* Ensure the sidebar can still be accessible without JavaScript */
@media (max-width: 768px) {
    #sidebar:not(.collapsed) {
        left: 0; /* Keep sidebar accessible without JS on small screens */
    }
}

</style>



<script src="js/script.js?v=1" defer></script>
</body>
</html>
