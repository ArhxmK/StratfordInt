<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 10px;
        position: relative;
        height: 130px; /* Increase the navbar height */
    }

    .navbar-nav {
        flex: 1;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .navbar-brand {
        position: relative;
        margin: 0 auto;
        z-index: 0;
    }

    .navbar-brand img {
        max-height: 120px; /* Ensure the logo scales with the navbar */
        height: 100%;
        vertical-align: middle; /* Keep the logo centered vertically */
    }

    .navbar-toggler {
        z-index: 1;
        position: absolute;
        left: 10px;
        top: 50%; /* Vertical centering */
        transform: translateY(-50%);
        background-color: transparent; /* Remove background color */
        border: none; /* Remove border */
        color: inherit; /* Maintain original icon color */
    }

    .navbar-toggler:hover, 
    .navbar-toggler:focus, 
    .navbar-toggler:active {
        outline: none; /* Remove focus outline */
        box-shadow: none; /* Remove box shadow */
        background-color: transparent; /* Prevent background color change */
        color: inherit; /* Maintain original icon color */
    }

    @media (max-width: 992px) {
        .navbar {
            height: 120px; /* Adjust height for smaller screens */
        }

        .navbar-brand img {
            width: 70px;
            height: 70px;
        }

        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .collapse:not(.show) {
            display: none !important;
        }

        .navbar-collapse {
            display: flex !important;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 999;
            transition: none; /* Ensure no shift or slide occurs */
        }

        .nav-item {
            width: 100%; /* Full width for items */
            text-align: center; /* Center text */
            padding: 10px 0; /* Add some padding for better spacing */
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand mx-auto d-lg-none" href="index.php">
        <img src="assets/img/logo.png" width="100" height="100" alt="Logo">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ourschool.php">School</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="news&blogs.php">News & Blogs</a>
            </li>
            <li class="nav-item d-none d-lg-block">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/img/logo.png" width="100" height="100" alt="Logo">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admission.php">Admissions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="course.php">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
            </li>
        </ul>
    </div>
</nav>
