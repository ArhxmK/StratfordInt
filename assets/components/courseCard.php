<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function courseCard($imgPath, $courseName, $description, $amount)
{
    $imagePath = htmlspecialchars($imgPath);
    $modalId = "course" . md5($courseName);
    ?>
    <style>
      .course-card {
            position: relative;
            overflow: hidden;
            border-radius: 15px; /* Add rounded corners here */
            margin-bottom: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            height: 400px;
            width: 100%; /* Ensures cards fill the column */
        }

        .course-card img {
            width: 100%;
            height: 100%; /* Ensure the image covers the entire container */
            display: block;
            transition: filter 0.3s;
            border-radius: 15px; /* Also round the image to match the card's border */
        }

        .course-card:hover img {
            filter: brightness(50%);
        }

        .course-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 20px;
            text-align: left;
            transition: background 0.3s;
            border-radius: 0 0 15px 15px; /* Round the bottom edges of the overlay */
        }

        .course-card:hover .course-overlay {
            background: rgba(0, 0, 0, 0.7);
        }

        .course-overlay h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .btn-read, .btn-apply {
            opacity: 1;
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: block;
            margin-top: 10px;
        }

        .modal-content {
            border-radius: 15px; /* Rounded corners for the modal content */
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }
    </style>

    <div class="col-12 col-md-6 col-lg-4"> <!-- Use col-lg-4 for three cards per row on large screens -->
        <div class="course-card position-relative">
            <img src="<?= $imagePath ?>" alt="Course image">
            <div class="course-overlay">
                <h2><?= htmlspecialchars($courseName) ?></h2>
                <p><strong>Amount:</strong> <?= htmlspecialchars($amount) ?> USD</p>
                <a href="#<?= $modalId ?>" class="btn-read" data-bs-toggle="modal">Read More</a>
                <a href="#apply<?= $modalId ?>" class="btn-apply" data-bs-toggle="modal">Apply Now</a>
            </div>
        </div>

        <!-- Modal for displaying full description -->
        <div id="<?= $modalId ?>" class="modal fade" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $modalId ?>Label"><?= htmlspecialchars($courseName) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?= nl2br(htmlspecialchars($description)) ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for applying to the course -->
        <div id="apply<?= $modalId ?>" class="modal fade" tabindex="-1" aria-labelledby="apply<?= $modalId ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="apply<?= $modalId ?>Label">Apply for <?= htmlspecialchars($courseName) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="process_course_application.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="studentId" class="form-label">Student ID</label>
                                <input type="text" class="form-control" name="student_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="studentName" class="form-label">Student Name</label>
                                <input type="text" class="form-control" name="student_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="grade" class="form-label">Grade</label>
                                <input type="text" class="form-control" name="grade" required>
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" class="form-control" name="amount" value="<?= htmlspecialchars($amount) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <p>Bank Account: 1234567890 - Make deposit via bank transfer or cash deposit.</p>
                            </div>
                            <div class="mb-3">
                                <label for="slip" class="form-label">Upload Bank Deposit Slip</label>
                                <input type="file" class="form-control" name="deposit_slip" required>
                            </div>
                            <input type="hidden" name="course_name" value="<?= htmlspecialchars($courseName) ?>">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
