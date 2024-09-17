<?php
require "../admin/dbh/connector.php"; // Include the database connection

// Debugging: Check if session staff ID is set
if (!isset($_SESSION['staff_id'])) {
    echo "Session not set!";
    exit;
}

$staffId = $_SESSION['staff_id'];

// Fetch staff details, including the profile image and name
$sql = "SELECT staffname, profile_image FROM staff WHERE staff_id = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo "SQL statement preparation error: " . mysqli_error($conn);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $staffId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    // If no result, check if the staff ID exists in the table
    echo "No staff found with ID: " . htmlspecialchars($staffId);
    exit;
}

$staff = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if the image path or staff name is missing
if (!$staff['profile_image'] || !$staff['staffname']) {
    $staff['profile_image'] = "../assets/default_profile.png"; // Default image if none exists
    $staff['staffname'] = "Unknown Staff"; // Fallback name
}
?>
<style>
 /* Sidebar Header with Smaller Profile Image */
.sidebar-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 15px;
}

.sidebar-header img {
    width: 70px;  /* Set smaller width */
    height: 70px; /* Set smaller height */
    border-radius: 50%; /* Keep the image circular */
    margin-bottom: 10px;
    border: 2px solid white;  /* Optional border around the image */
}

.sidebar-header h3 {
    font-size: 16px; /* Adjust font size for the name */
    margin-top: 10px;
    line-height: 1.2;
    color: white;
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

<div id="sidebar">
    <div class="sidebar-header">
        <br><br><br>
        <!-- Display Profile Image in a circular format -->
        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="profile-img">
        <h3><?php echo htmlspecialchars($staffName); ?></h3>
    </div>
    <ul>
        <li><a href="../admin/staffhome.php"><i class="fa fa-home"></i> Dashboard</a></li>
        <li><a href="../admin/staff_addnews&blogs.php"><i class="fa fa-newspaper"></i> News & Blogs</a></li>
        <li><a href="../admin/staff_addcourse.php"><i class="fa fa-book"></i> Course</a></li>
        <li><a href="../admin/staff_manageadmissionreq.php"><i class="fa fa-file-alt"></i> Admission Request</a></li>
        <li><a href="../admin/staff_admissions.php"><i class="fa fa-graduation-cap"></i> Admission</a></li>
        <li><a href="../admin/staff_viewmanagecourses.php"><i class="fa fa-book"></i> Manage Courses</a></li>
        <li><a href="../admin/staff_viewmanagenews.php"><i class="fa fa-book"></i> Manage News</a></li>
        <li><a href="../admin/staff_managecoursereq.php"><i class="fa fa-book"></i> Course Requests</a></li>
        <li><a href="../admin/staff_manageadmissionpayment.php"><i class="fa fa-book"></i> Admission Payments</a></li>
        <li><a href="../admin/login.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
