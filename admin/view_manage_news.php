<?php
session_start();
define("PAGE_TITLE", "View and Manage News & Blogs");
require "../admin/includes/header.php";
require "../admin/dbh/connector.php"; // Include the database connection

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch records based on search query
function fetchBlogRecords($conn, $search = '') {
    $query = "SELECT * FROM blogs";
    if ($search) {
        $query .= " WHERE b_id LIKE '%$search%' OR subject LIKE '%$search%'";
    }
    return mysqli_query($conn, $query);
}

// Delete a record from the blogs table
function deleteBlogRecord($conn, $id) {
    $query = "DELETE FROM blogs WHERE b_id = '$id'";
    return mysqli_query($conn, $query);
}

// Update a record in the blogs table
function updateBlogRecord($conn, $id, $subject, $article) {
    $query = "UPDATE blogs SET subject='$subject', article='$article' WHERE b_id='$id'";
    return mysqli_query($conn, $query);
}

// Handle delete button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $deleteId = $_POST["delete"];
    deleteBlogRecord($conn, $deleteId);
    header("Location: view_manage_news.php");
    exit();
}

// Handle update button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $updateId = $_POST["update"];
    $subject = $_POST["subject"];
    $article = $_POST["article"];
    updateBlogRecord($conn, $updateId, $subject, $article);
    header("Location: view_manage_news.php");
    exit();
}

// Handle search query
$search = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search = $_GET["search"];
}

// Fetch records to display
$result = fetchBlogRecords($conn, $search);
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
        <h2>View News & Blogs</h2>
        <br><br>
        <!-- Search Form -->
        <form method="get" action="view_manage_news.php">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search by ID or Subject" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <br>

        <!-- Blogs Table -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Article</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($records as $record) {
                    echo '<tr id="record-' . htmlspecialchars($record['b_id']) . '">';
                    echo '<td data-label="ID">' . htmlspecialchars($record['b_id']) . '</td>';
                    echo '<td data-label="Subject">' . htmlspecialchars($record['subject']) . '</td>';
                    echo '<td data-label="Article">' . htmlspecialchars($record['article']) . '</td>';
                    echo '<td data-label="Actions">';
                    echo '<a href="#" class="edit-icon" onclick="editRecord(\'' . $record['b_id'] . '\')">';
                    echo '<i class="fa fa-edit"></i>';
                    echo '</a>';
                    echo '<a href="#" class="delete-icon" onclick="deleteRecord(\'' . $record['b_id'] . '\')">';
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
            <h3>Edit Blog Record</h3>
            <form id="edit-form" method="post" action="view_manage_news.php">
                <input type="hidden" name="update" id="edit-blog-id">
                <label for="edit-subject">Subject:</label>
                <input type="text" name="subject" id="edit-subject" required>
                <label for="edit-article">Article:</label>
                <textarea name="article" id="edit-article" rows="4" required></textarea>
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

            function deleteRecord(blogId) {
                if (confirm("Are you sure you want to delete this record?")) {
                    var form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "view_manage_news.php");

                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "delete");
                    input.setAttribute("value", blogId);

                    form.appendChild(input);
                    document.body.appendChild(form);

                    form.submit();
                }
            }

            function editRecord(blogId) {
                var row = document.getElementById("record-" + blogId);
                var cells = row.getElementsByTagName("td");

                document.getElementById("edit-blog-id").value = blogId;
                document.getElementById("edit-subject").value = cells[1].innerText;
                document.getElementById("edit-article").value = cells[2].innerText;

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

    /* Search Bar */
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
