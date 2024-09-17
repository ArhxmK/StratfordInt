<?php
define("PAGE_TITLE", "View and Manage Admission Records");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php"; // Include the database connection

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


//profile image//
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
//end

// Include the necessary files for SMS sending
require_once('./../PHP-SMS-API-lib/send_sms_impl.php');

// Fetch records based on search query
function fetchAdmissionRecords($conn, $search = '') {
    $query = "SELECT * FROM request_admission";
    if ($search) {
        $query .= " WHERE student_name LIKE '%$search%' OR parent_name LIKE '%$search%'";
    }
    return mysqli_query($conn, $query);
}

// Delete a record from the request_admission table
function deleteAdmissionRecord($conn, $id) {
    $query = "DELETE FROM request_admission WHERE a_id = '$id'";
    return mysqli_query($conn, $query);
}

// Update a record in the request_admission table
function updateAdmissionRecord($conn, $id, $student_name, $mobile, $email) {
    $query = "UPDATE request_admission SET student_name='$student_name', mobile='$mobile', email='$email' WHERE a_id='$id'";
    return mysqli_query($conn, $query);
}

// Approve a record in the request_admission table
function approveAdmissionRecord($conn, $id, $token) {
    // Fetch the record data
    $query = "SELECT * FROM request_admission WHERE a_id='$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("SQL Error: " . mysqli_error($conn));
        return;
    }

    $record = mysqli_fetch_assoc($result);

    if ($record) {
        $student_name = $record['student_name'];
        $mobile = $record['mobile'];
        $grade = $record['admission_class'];

        // Fetch the maximum s_id from the students table
        $max_s_id_query = "SELECT MAX(CAST(SUBSTRING(s_id, 2) AS UNSIGNED)) AS max_id FROM students";
        $max_s_id_result = mysqli_query($conn, $max_s_id_query);
        if (!$max_s_id_result) {
            error_log("SQL Error: " . mysqli_error($conn));
            return;
        }

        $max_s_id_row = mysqli_fetch_assoc($max_s_id_result);
        $next_s_id = 'S' . str_pad($max_s_id_row['max_id'] + 1, 3, '0', STR_PAD_LEFT);

        // Insert data into students table
        $insert_query = "INSERT INTO students (s_id, student_name, grade, mobile) 
                         VALUES ('$next_s_id', '$student_name', '$grade', '$mobile')";
        if (!mysqli_query($conn, $insert_query)) {
            error_log("SQL Error: " . mysqli_error($conn));
            return;
        }

        // Send SMS
        $sendSmsImpl = new SendSMSImpl();
        $sendTextBody = new SendTextBody();
        $sendTextBody->setSourceAddress('KanchTest'); // Replace with your desired source address
        $sendTextBody->setMessage("The Admission request for $student_name has been approved. Please Contact 0722991717 to schedule an interview.");
        $sendTextBody->setTransactionId(time());
        $sendTextBody->setMsisdn($sendSmsImpl->setMsisdns(array($mobile)));
        
        // Log attempt to send SMS
        error_log("Attempting to send approval SMS to " . $mobile);

        $sendResponse = $sendSmsImpl->sendText($sendTextBody, $token);

        // Debug the response
        error_log("SMS send response: " . print_r($sendResponse, true));
        if ($sendResponse->getStatus() == 'success') {
            error_log("SMS sent successfully to " . $mobile);
        } else {
            error_log("Failed to send SMS: " . $sendResponse->getComment());
        }
    }

    // Remove the record from request_admission table
    deleteAdmissionRecord($conn, $id);
}


// Reject a record in the request_admission table
function rejectAdmissionRecord($conn, $id, $token) {
    // Fetch the record data
    $query = "SELECT * FROM request_admission WHERE a_id='$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("SQL Error: " . mysqli_error($conn));
        return;
    }

    $record = mysqli_fetch_assoc($result);

    if ($record) {
        $mobile = $record['mobile'];

        // Send SMS
        $sendSmsImpl = new SendSMSImpl();
        $sendTextBody = new SendTextBody();
        $sendTextBody->setSourceAddress('KanchTest'); // Replace with your desired source address
        $sendTextBody->setMessage("Sorry, your admission request has been rejected. Please contact 0722991717 for more clarification.");
        $sendTextBody->setTransactionId(time());
        $sendTextBody->setMsisdn($sendSmsImpl->setMsisdns(array($mobile)));

        // Log attempt to send SMS
        error_log("Attempting to send rejection SMS to " . $mobile);

        $sendResponse = $sendSmsImpl->sendText($sendTextBody, $token);

        // Debug the response
        error_log("SMS send response: " . print_r($sendResponse, true));
        if ($sendResponse->getStatus() == 'success') {
            error_log("SMS sent successfully to " . $mobile);
        } else {
            error_log("Failed to send SMS: " . $sendResponse->getComment());
        }
    }

    // Remove the record from request_admission table
    deleteAdmissionRecord($conn, $id);
}

// Handle delete button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $deleteId = $_POST["delete"];
    deleteAdmissionRecord($conn, $deleteId);
    header("Location: manageadmissionreq.php");
    exit();
}

// Handle update button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $updateId = $_POST["update"];
    $student_name = $_POST["student_name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    updateAdmissionRecord($conn, $updateId, $student_name, $mobile, $email);
    header("Location: manageadmissionreq.php");
    exit();
}

// Handle approve button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["approve"])) {
    $tokenPath = realpath('sms/token.txt');
    $token = file_get_contents($tokenPath);
    if ($token === false) {
        error_log('Failed to read token from ' . $tokenPath);
        die('Failed to read token from token.txt');
    }
    approveAdmissionRecord($conn, $_POST["approve"], $token);
    header("Location: manageadmissionreq.php");
    exit();
}

// Handle reject button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reject"])) {
    $tokenPath = realpath('sms/token.txt');
    $token = file_get_contents($tokenPath);
    if ($token === false) {
        error_log('Failed to read token from ' . $tokenPath);
        die('Failed to read token from token.txt');
    }
    rejectAdmissionRecord($conn, $_POST["reject"], $token);
    header("Location: manageadmissionreq.php");
    exit();
}

// Handle search query
$search = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search = $_GET["search"];
}

// Fetch records to display
$result = fetchAdmissionRecords($conn, $search);
$records = [];
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}


require "../admin/includes/sidebarstaff.php";
?>

<div class="wrapper">
    <!-- Toggle Icon Outside Sidebar -->
    <button id="sidebarToggle">&#9776;</button>
</div>
<br><br>
<div class="main-content" id="main-content">
    <div class="r-container">
        <h2>View Admission Records</h2>

        <!-- Search Form -->
        <form method="get" action="manageadmissionreq.php">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by Student or Parent Name" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <br>

        <!-- Admission Table -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Actions</th>
                    <th>Approve/Reject</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record) : ?>
                    <tr id="record-<?= htmlspecialchars($record['a_id']); ?>">
                        <td><?= htmlspecialchars($record['student_name']); ?></td>
                        <td><?= htmlspecialchars($record['mobile']); ?></td>
                        <td><?= htmlspecialchars($record['email']); ?></td>
                        <td>
                            <a href="../admin/download_admission_pdf.php?id=<?= $record['a_id']; ?>" target="_blank" class="download-button">Download PDF</a>
                        </td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="approve" value="<?= htmlspecialchars($record['a_id']); ?>">
                                <button type="submit" class="approve-button">Approve</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="reject" value="<?= htmlspecialchars($record['a_id']); ?>">
                                <button type="submit" class="reject-button">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript for Sidebar Toggle and Content -->
<script>
    window.onload = function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content') || document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (sidebar && content && sidebarToggle) {
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
        } else {
            console.error("Required elements not found (#sidebar, #content or #main-content, #sidebarToggle). Check HTML structure.");
        }
    };
</script>

<!-- Styling -->
<style>
 /* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
}

.wrapper {
    display: flex;
    align-items: flex-start;
}

/* Sidebar Styles */
#sidebar {
    width: 250px;
    background: #061355;
    color: white;
    position: fixed;
    height: 100%;
    transition: all 0.3s ease;
    overflow: hidden;
    z-index: 1000;
    left: 0;
}

/* Sidebar hidden off-screen for smaller screens */
#sidebar.collapsed {
    width: 0;
    left: -250px;
}

/* Sidebar partially visible on mobile screens */
@media (max-width: 768px) {
    #sidebar {
        width: 70%;  /* Sidebar covers 70% of the screen */
        left: 0;
    }

    #sidebar.collapsed {
        left: -70%;  /* Slide the sidebar 70% off the screen */
    }
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

/* Content Styles */
#main-content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    transition: all 0.3s ease;
}

#main-content.collapsed {
    margin-left: 0;
    width: 100%;
}

h2 {
    font-size: 24px;
    color: #003366;
    margin-bottom: 20px;
}

.search-bar {
    display: flex;
    justify-content: space-between;
    max-width: 300px;
    margin-bottom: 20px;
}

.search-bar input {
    width: 80%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.search-bar button {
    padding: 8px 12px;
    background-color: #003366;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 16px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.styled-table thead tr {
    background-color: #fff;
    color: #333;
}

.styled-table th, .styled-table td {
    padding: 12px 15px;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

.download-button {
    padding: 8px 12px;
    background-color: #ff7f00;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.download-button:hover {
    background-color: #e06c00;
}

/* Button Styles */
.approve-button {
    padding: 8px 12px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

.reject-button {
    padding: 8px 12px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.approve-button:hover {
    background-color: #218838;
}

.reject-button:hover {
    background-color: #c82333;
}

/* Responsive Table and Sidebar Behavior for Smaller Screens */
@media (max-width: 768px) {
    #main-content {
        margin-left: 0;
        width: 100%;
    }

    #sidebar {
        width: 70%;  /* Sidebar covers 70% of the screen */
    }

    #sidebar.collapsed {
        left: -70%;  /* Slide the sidebar 70% off the screen */
    }

    .styled-table thead {
        display: none;
    }

    .styled-table tr {
        display: block;
        margin-bottom: 10px;
    }

    .styled-table td {
        display: block;
        text-align: right;
        font-size: 14px;
        border: none;
    }

    .styled-table td::before {
        content: attr(data-label);
        float: left;
        text-transform: uppercase;
        font-weight: bold;
    }

    .styled-table td.actions {
        text-align: center;
    }
}

</style>


<script src="js/script.js?v=1" defer></script>
</body>
</html>
