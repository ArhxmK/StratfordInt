<?php
define("PAGE_TITLE", "View and Manage Admission Payments");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php"; // Include the database connection


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

// Fetch pending records
function fetchPaymentRecords($conn, $search = '') {
    $query = "SELECT * FROM admissionpayment WHERE status IS NULL"; // Fetch only pending records
    if ($search) {
        $query .= " AND (StudentId LIKE '%$search%' OR Mobile LIKE '%$search%')";
    }
    return mysqli_query($conn, $query);
}

// Fetch approved or rejected records
function fetchProcessedPayments($conn) {
    $query = "SELECT * FROM admissionpayment WHERE status IS NOT NULL"; // Fetch only processed records
    return mysqli_query($conn, $query);
}

// Update payment status in admissionpayment table
function updatePaymentStatus($conn, $id, $status, $message, $token) {
    // Use StudentId instead of payment_id
    $query = "UPDATE admissionpayment SET status = ? WHERE StudentId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        // Send SMS
        sendPaymentSMS($conn, $id, $message, $token);
    } else {
        error_log("SQL Error: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}

// Send SMS with confirmation or rejection message
function sendPaymentSMS($conn, $id, $message, $token) {
    $query = "SELECT StudentId, Mobile FROM admissionpayment WHERE StudentId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $student_id, $mobile);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Replace placeholders in the message with actual data
    $message = str_replace('{student_id}', $student_id, $message);

    // Log the SMS message to be sent
    error_log("SMS Message to $mobile: " . $message);

    // Prepare the SMS sending logic
    $sendSmsImpl = new SendSMSImpl();
    $sendTextBody = new SendTextBody();
    $sendTextBody->setSourceAddress('KanchTest'); // Your source address
    $sendTextBody->setMessage($message);
    $sendTextBody->setTransactionId(time());
    $sendTextBody->setMsisdn($sendSmsImpl->setMsisdns([$mobile]));

    // Send SMS via the SMS API
    $sendResponse = $sendSmsImpl->sendText($sendTextBody, $token);

    // Log the response from the SMS API
    error_log("SMS send response: " . print_r($sendResponse, true));

    // Check if the SMS was successfully sent
    if ($sendResponse->getStatus() == 'success') {
        error_log("SMS successfully sent to " . $mobile);
    } else {
        error_log("Failed to send SMS to " . $mobile . ". Error: " . $sendResponse->getComment());
    }
}

// Handle confirm button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm"])) {
    $tokenPath = realpath('sms/token.txt');
    $token = file_get_contents($tokenPath);
    if ($token === false) {
        error_log('Failed to read token from ' . $tokenPath);
        die('Failed to read token from token.txt');
    }
    $message = "Admission Payment Successful for the StudentID = {student_id}";
    updatePaymentStatus($conn, $_POST["confirm"], 'confirmed', $message, $token);
    header("Location: manageadmissionpayment.php");
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
    $message = "Payment failed for the StudentId- {student_id}. Please contact 0772133583 for more clarification.";
    updatePaymentStatus($conn, $_POST["reject"], 'rejected', $message, $token);
    header("Location: manageadmissionpayment.php");
    exit();
}

// Handle search query
$search = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search = $_GET["search"];
}

// Fetch pending and processed records
$pendingRecords = fetchPaymentRecords($conn, $search);
$processedPayments = fetchProcessedPayments($conn);

// Close the database connection
mysqli_close($conn);

require "../admin/includes/sidebarstaff.php";
?>

<div class="wrapper">
    <!-- Toggle Icon Outside Sidebar -->
    <button id="sidebarToggle">&#9776;</button>
</div>
<br><br>
<div class="main-content" id="main-content">
    <div class="r-container">
        <h2>Manage Admission Payments</h2>

        <!-- Search Form -->
        <form method="get" action="manageadmissionpayment.php">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by Student ID or Mobile" value="<?= htmlspecialchars($search ?? ''); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <br>

        <!-- Pending Payments Table -->
        <h3>Pending Payments</h3>
        <br>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Amount</th>
                    <th>Mobile</th>
                    <th>Receipt</th>
                    <th>Actions</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRecords as $record) : ?>
                    <tr id="record-<?= htmlspecialchars($record['StudentId'] ?? ''); ?>">
                        <td><?= htmlspecialchars($record['StudentId'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($record['Amount'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($record['Mobile'] ?? ''); ?></td>
                        <td>
                            <a href="download_adreceipt.php?id=<?= htmlspecialchars($record['StudentId'] ?? ''); ?>" target="_blank" class="download-button">Download Receipt</a>
                        </td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="confirm" value="<?= htmlspecialchars($record['StudentId'] ?? ''); ?>">
                                <button type="submit" class="confirm-button">Confirm</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="reject" value="<?= htmlspecialchars($record['StudentId'] ?? ''); ?>">
                                <button type="submit" class="reject-button">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Processed Payments Table -->
        <h3>Processed Payments (Confirmed/Rejected)</h3>
        <br>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Amount</th>
                    <th>Mobile</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($processedPayments as $record) : ?>
                    <tr id="record-<?= htmlspecialchars($record['StudentId'] ?? ''); ?>">
                        <td><?= htmlspecialchars($record['StudentId'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($record['Amount'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($record['Mobile'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($record['status'] ?? ''); ?></td>
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

#sidebar.collapsed {
    width: 0;
    left: -250px;
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

.confirm-button {
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

.confirm-button:hover {
    background-color: #218838;
}

.reject-button:hover {
    background-color: #c82333;
}

@media (max-width: 768px) {
    #main-content {
        margin-left: 0;
        width: 100%;
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
