<?php
session_start();
define("PAGE_TITLE", "Dashboard");
require "../admin/includes/header.php";
require "../admin/includes/sidebar.php";
?>

    <div class="wrapper">
        <!-- Toggle Icon Outside Sidebar -->
        <button id="sidebarToggle">&#9776;</button>

        <!-- Content -->
        <div id="content">
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


    <script src="js/script.js?v=1"></script>

</body>
</html>
