<?php
function newsCard($imgPath, $subject, $article, $b_id)
{
    $imagePath = htmlspecialchars($imgPath);
    $modalId = "news" . htmlspecialchars($b_id);
    ?>
    <style>
        .news-card {
            position: relative;
            overflow: hidden;
            border-radius: 0; /* Square edges */
            margin-bottom: 40px; /* Increased space between rows */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            height: 400px; /* Increased height */
        }

        .news-card img {
            width: 100%;
            height: 100%; /* Ensure the image covers the entire container */
            display: block;
            transition: filter 0.3s;
        }

        .news-card:hover img {
            filter: brightness(50%);
        }

        .news-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 20px;
            text-align: left;
            transition: background 0.3s;
        }

        .news-card:hover .news-overlay {
            background: rgba(0, 0, 0, 0.7);
        }

        .news-overlay h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .btn-read {
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
            border-radius: 0; /* Square edges */
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }
    </style>

    <div class="col-12 col-md-4"> <!-- Ensure three containers per row -->
        <div class="news-card position-relative">
            <img src="<?= $imagePath ?>" alt="News image">
            <div class="news-overlay">
                <h2><?= htmlspecialchars($subject) ?></h2>
                <a href="#<?= $modalId ?>" class="btn-read" data-bs-toggle="modal">Read More</a>
            </div>
        </div>

        <!-- Modal for displaying full article -->
        <div id="<?= $modalId ?>" class="modal fade" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $modalId ?>Label"><?= htmlspecialchars($subject) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?= nl2br(htmlspecialchars($article)) ?></p>
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
