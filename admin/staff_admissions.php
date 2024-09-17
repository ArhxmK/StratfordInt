<?php
define("PAGE_TITLE", "View Approved Admission Records");
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

// Fetch approved records from the students table
function fetchApprovedAdmissions($conn) {
    $query = "SELECT * FROM students";
    return mysqli_query($conn, $query);
}

// Delete a record from the students table
function deleteStudentRecord($conn, $id) {
    $query = "DELETE FROM students WHERE s_id = '$id'";
    return mysqli_query($conn, $query);
}

// Update a record in the students table
function updateStudentRecord($conn, $id, $name, $grade, $mobile) {
    $query = "UPDATE students SET student_name='$name', grade='$grade', mobile='$mobile' WHERE s_id='$id'";
    return mysqli_query($conn, $query);
}

// Handle delete button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $deleteId = $_POST["delete"];
    deleteStudentRecord($conn, $deleteId);
    header("Location: admissions.php");
    exit();
}

// Handle update button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $updateId = $_POST["update"];
    $name = $_POST["student_name"];
    $grade = $_POST["grade"];
    $mobile = $_POST["mobile"];
    updateStudentRecord($conn, $updateId, $name, $grade, $mobile);
    header("Location: admissions.php");
    exit();
}

// Fetch records to display
$result = fetchApprovedAdmissions($conn);
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
        <h2>View Approved Admission Records</h2>
<br><br>
        <!-- Records Table -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($records as $record) {
                    echo '<tr id="record-' . htmlspecialchars($record['s_id']) . '">';
                    echo '<td data-label="Student ID">' . htmlspecialchars($record['s_id']) . '</td>';
                    echo '<td data-label="Student Name">' . htmlspecialchars($record['student_name']) . '</td>';
                    echo '<td data-label="Grade">' . htmlspecialchars($record['grade']) . '</td>';
                    echo '<td data-label="Mobile">' . htmlspecialchars($record['mobile']) . '</td>';
                    
                    // Actions column with edit and delete icons
                    echo '<td data-label="Actions">';
                    echo '<a href="#" class="edit-icon" onclick="editRecord(\'' . htmlspecialchars($record['s_id']) . '\')">';
                    echo '<i class="fa fa-edit"></i>';
                    echo '</a>';
                    echo '<a href="#" class="delete-icon" onclick="deleteRecord(\'' . htmlspecialchars($record['s_id']) . '\')">';
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
            <h3>Edit Student Record</h3>
            <form id="edit-form" method="post" action="admissions.php">
                <input type="hidden" name="update" id="edit-student-id">
                <label for="edit-student-name">Student Name:</label>
                <input type="text" name="student_name" id="edit-student-name" required>
                <label for="edit-grade">Grade:</label>
                <input type="text" name="grade" id="edit-grade" required>
                <label for="edit-mobile">Mobile:</label>
                <input type="text" name="mobile" id="edit-mobile" required>
                <button type="submit"><i class="fa fa-save"></i> Save Changes</button>
                <button type="button" onclick="cancelEdit()"><i class="fa fa-times"></i> Cancel</button>
            </form>
        </div>

        <!-- JavaScript for Sidebar Toggle and Actions -->
        <script>
            // Sidebar Toggle Functionality
            window.onload = function() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('main-content');
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
                    console.error("Required elements not found (#sidebar, #main-content, #sidebarToggle).");
                }
            };

            function deleteRecord(studentId) {
                if (confirm("Are you sure you want to delete this record?")) {
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "admissions.php");

                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "delete");
                    input.setAttribute("value", studentId);

                    form.appendChild(input);
                    document.body.appendChild(form);

                    form.submit();
                }
            }

            function editRecord(studentId) {
                var row = document.getElementById("record-" + studentId);
                var cells = row.getElementsByTagName("td");

                document.getElementById("edit-student-id").value = studentId;
                document.getElementById("edit-student-name").value = cells[1].innerText;
                document.getElementById("edit-grade").value = cells[2].innerText;
                document.getElementById("edit-mobile").value = cells[3].innerText;

                document.getElementById("edit-form-container").style.display = "block";
            }

            function cancelEdit() {
                document.getElementById("edit-form-container").style.display = "none";
            }
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

    /* Main content initially shifted when sidebar is open */
    #main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
    }

    /* When sidebar is collapsed, content expands */
    #main-content.collapsed {
        margin-left: 0;
        width: 100%;
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

    /* Sidebar hidden off-screen for smaller screens */
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

    .edit-icon, .delete-icon {
        color: #003366;
        margin-right: 15px;
    }

    .edit-icon:hover, .delete-icon:hover {
        color: #f7f7f7;
    }

    /* Responsive Design */
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
