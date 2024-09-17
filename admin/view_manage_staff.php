<?php
session_start();
define("PAGE_TITLE", "View and Manage Staff");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php"; // Include the database connection

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch records based on search query
function fetchStaffRecords($conn, $search = '') {
    $query = "SELECT * FROM staff";
    if ($search) {
        $query .= " WHERE staff_id LIKE '%$search%' OR staffname LIKE '%$search%'";
    }
    return mysqli_query($conn, $query);
}

// Delete a record from the staff table
function deleteStaffRecord($conn, $id) {
    $query = "DELETE FROM staff WHERE staff_id = '$id'";
    return mysqli_query($conn, $query);
}

// Update a record in the staff table
function updateStaffRecord($conn, $id, $name, $role, $age, $nic) {
    $query = "UPDATE staff SET staffname='$name', role='$role', age='$age', nic='$nic' WHERE staff_id='$id'";
    return mysqli_query($conn, $query);
}

// Toggle account status
function toggleAccountStatus($conn, $id, $currentStatus) {
    $newStatus = $currentStatus ? 0 : 1;
    $query = "UPDATE staff SET account_status=? WHERE staff_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $newStatus, $id);
    return mysqli_stmt_execute($stmt);
}

// Handle delete button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $deleteId = $_POST["delete"];
    deleteStaffRecord($conn, $deleteId);
    header("Location: view_manage_staff.php");
    exit();
}

// Handle update button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $updateId = $_POST["update"];
    $name = $_POST["name"];
    $role = $_POST["role"];
    $age = $_POST["age"];
    $nic = $_POST["nic"];
    updateStaffRecord($conn, $updateId, $name, $role, $age, $nic);
    header("Location: view_manage_staff.php");
    exit();
}

// Handle account status toggle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_status"])) {
    $toggleId = $_POST["toggle_status"];
    $currentStatus = $_POST["current_status"];
    toggleAccountStatus($conn, $toggleId, $currentStatus);
    header("Location: view_manage_staff.php");
    exit();
}

// Handle search query
$search = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search = $_GET["search"];
}

// Fetch records to display
$result = fetchStaffRecords($conn, $search);
$records = [];
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

require "../admin/includes/sidebar.php";
?>

<div class="wrapper">
    <!-- Toggle Icon Outside Sidebar -->
    <button id="sidebarToggle">&#9776;</button>
</div>
<br><br>
<div class="main-content" id="main-content">
    <div class="r-container">
        <h2>View Staff Records</h2>
        <br><br>
        <!-- Search Form -->
        <form method="get" action="view_manage_staff.php">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by Staff ID or Name" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <br>

        <!-- Staff Table -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Role</th>
                    <th>Age</th>
                    <th>NIC No</th>
                    <th>Account Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($records as $record) {
                    // Display profile image in a circular format
                    echo '<tr id="record-' . htmlspecialchars($record['staff_id']) . '">';
                    echo '<td data-label="Profile"><img src="' . htmlspecialchars($record['profile_image']) . '" style="width:50px; height:50px; border-radius:50%;" alt="Profile Image"></td>';
                    echo '<td data-label="Staff ID">' . htmlspecialchars($record['staff_id']) . '</td>';
                    echo '<td data-label="Staff Name">' . htmlspecialchars($record['staffname']) . '</td>';
                    echo '<td data-label="Role">' . htmlspecialchars($record['role']) . '</td>';
                    echo '<td data-label="Age">' . htmlspecialchars($record['age']) . '</td>';
                    echo '<td data-label="NIC No">' . htmlspecialchars($record['nic']) . '</td>';
                    echo '<td data-label="Account Status">';
                    echo '<form method="post" action="view_manage_staff.php" style="display:inline;">';
                    echo '<input type="hidden" name="toggle_status" value="' . htmlspecialchars($record['staff_id']) . '">';
                    echo '<input type="hidden" name="current_status" value="' . htmlspecialchars($record['account_status']) . '">';
                    echo '<button type="submit" class="status-button">';
                    echo $record['account_status'] ? '<i class="fa fa-toggle-on"></i>' : '<i class="fa fa-toggle-off"></i>';
                    echo '</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '<td data-label="Actions">';
                    echo '<a href="#" class="edit-icon" onclick="editRecord(\'' . $record['staff_id'] . '\')">';
                    echo '<i class="fa fa-edit"></i>';
                    echo '</a>';
                    echo '<a href="#" class="delete-icon" onclick="deleteRecord(\'' . $record['staff_id'] . '\')">';
                    echo '<i class="fa fa-trash-alt"></i>';
                    echo '</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Editable Form Container -->
        <div id="edit-form-container" style="display:none;">
            <h3>Edit Staff Record</h3>
            <form id="edit-form" method="post" action="view_manage_staff.php">
                <input type="hidden" name="update" id="edit-staff-id">
                <label for="edit-staff-name">Staff Name:</label>
                <input type="text" name="name" id="edit-staff-name" required>
                <label for="edit-staff-role">Role:</label>
                <input type="text" name="role" id="edit-staff-role" required>
                <label for="edit-staff-age">Age:</label>
                <input type="number" name="age" id="edit-staff-age" required>
                <label for="edit-staff-nic">NIC No:</label>
                <input type="text" name="nic" id="edit-staff-nic" required>
                <button type="submit"><i class="fa fa-save"></i> Save Changes</button>
                <button type="button" onclick="cancelEdit()"><i class="fa fa-times"></i> Cancel</button>
            </form>
        </div>

        <!-- JavaScript for Actions -->
        <script>
            function deleteRecord(staffId) {
                if (confirm("Are you sure you want to delete this record?")) {
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "view_manage_staff.php");

                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "delete");
                    input.setAttribute("value", staffId);

                    form.appendChild(input);
                    document.body.appendChild(form);

                    form.submit();
                }
            }

            function editRecord(staffId) {
                var row = document.getElementById("record-" + staffId);
                var cells = row.getElementsByTagName("td");

                document.getElementById("edit-staff-id").value = staffId;
                document.getElementById("edit-staff-name").value = cells[2].innerText;
                document.getElementById("edit-staff-role").value = cells[3].innerText;
                document.getElementById("edit-staff-age").value = cells[4].innerText;
                document.getElementById("edit-staff-nic").value = cells[5].innerText;

                document.getElementById("edit-form-container").style.display = "block";
            }

            function cancelEdit() {
                document.getElementById("edit-form-container").style.display = "none";
            }

            window.onload = function() {
                // Sidebar toggle functionality
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('main-content');
                const sidebarToggle = document.getElementById('sidebarToggle');

                let isSidebarCollapsed = sidebar.classList.contains('collapsed');

                sidebarToggle.addEventListener('click', function() {
                    if (isSidebarCollapsed) {
                        sidebar.classList.remove('collapsed');
                        content.classList.remove('collapsed');
                    } else {
                        sidebar.classList.add('collapsed');
                        content.classList.add('collapsed');
                    }
                    isSidebarCollapsed = !isSidebarCollapsed;
                });
            };
        </script>
    </div>
</div>

<!-- Styling -->
<style>
 .main-content {
    padding: 20px;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

#main-content {
    margin-left: 250px;
    width: calc(100% - 250px);
    transition: all 0.3s ease;
}

#main-content.collapsed {
    margin-left: 0;
    width: 100%;
}

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

@media (max-width: 768px) {
    #main-content {
        margin-left: 0;
        width: 100%;
    }

    #sidebar {
        width: 70%;
    }

    #sidebar.collapsed {
        left: -70%;
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

.search-bar button:hover {
    background-color: #005299;
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
    color: #000;
    text-align: left;
}

.styled-table th, .styled-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:hover {
    background-color: #f9f9f9;
}

.styled-table td img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.edit-icon, .delete-icon {
    color: #003366;
    margin-right: 15px;
}

.edit-icon:hover, .delete-icon:hover {
    color: #fff;
}

@media screen and (max-width: 768px) {
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
        position: relative;
        padding-left: 50%;
    }

    .styled-table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 15px;
        font-weight: bold;
        text-align: left;
    }

    .styled-table td.actions {
        text-align: center;
    }
}
</style>

<script src="js/script.js?v=1" defer></script>
</body>
</html>
