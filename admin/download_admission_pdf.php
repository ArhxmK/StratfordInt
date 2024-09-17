<?php
require "../admin/dbh/connector.php";
require '../vendor/autoload.php'; // Ensure you have TCPDF autoload.php included
require '../admin/includes/MyTCPDF.php'; // Include your custom TCPDF class

use TCPDF;

// Function to convert image to base64
function convertImageToBase64($imagePath) {
    if (file_exists($imagePath)) {
        $imageData = file_get_contents($imagePath);
        return base64_encode($imageData);
    }
    return '';
}

// Check if the ID parameter is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id <= 0) {
        die("Invalid ID parameter provided.");
    }
} else {
    die("No ID parameter provided.");
}

// Verify the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the specific admission request
$query = "SELECT * FROM request_admission WHERE a_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Record not found.");
}

// Paths to the image files
$passportPhotoPath = "../" . $row['passport_size_photo'];
$birthCertificatePath = "../" . $row['birth_certificate_copy'];
$parentsNicPath = "../" . $row['parents_nic_copy'];

// Convert images to base64
$passportPhotoBase64 = convertImageToBase64($passportPhotoPath);
$birthCertificateBase64 = convertImageToBase64($birthCertificatePath);
$parentsNicBase64 = convertImageToBase64($parentsNicPath);

// Create a new PDF document using MyTCPDF
$pdf = new MyTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Admission Record');
$pdf->SetSubject('Admission Record');
$pdf->SetKeywords('TCPDF, PDF, admission, record');

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add content with <br> tags and margin styling for spacing
$html = '<style>
    h2 {
        margin-top: 20px;
        margin-bottom: 10px;
    }
    img {
        display: block;
        margin-bottom: 20px;
        width: 150px;
        height: auto;
    }
    table {
        width: 100%;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    .image-section {
        margin-bottom: 40px;
    }
</style>';

$html .= '<h1>Admission Record</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr><th>Field</th><th>Value</th></tr>';
$html .= '<tr><td>Student Name</td><td>' . htmlspecialchars($row['student_name']) . '</td></tr>';
$html .= '<tr><td>Date of Birth</td><td>' . htmlspecialchars($row['dob']) . '</td></tr>';
$html .= '<tr><td>Nationality</td><td>' . htmlspecialchars($row['nationality']) . '</td></tr>';
$html .= '<tr><td>Religion</td><td>' . htmlspecialchars($row['religion']) . '</td></tr>';
$html .= '<tr><td>Sex</td><td>' . htmlspecialchars($row['sex']) . '</td></tr>';
$html .= '<tr><td>Last School</td><td>' . htmlspecialchars($row['last_school']) . '</td></tr>';
$html .= '<tr><td>Last Grade</td><td>' . htmlspecialchars($row['last_grade']) . '</td></tr>';
$html .= '<tr><td>Admission Class</td><td>' . htmlspecialchars($row['admission_class']) . '</td></tr>';
$html .= '<tr><td>Fixed Line</td><td>' . htmlspecialchars($row['fixed_line']) . '</td></tr>';
$html .= '<tr><td>Mobile</td><td>' . htmlspecialchars($row['mobile']) . '</td></tr>';
$html .= '<tr><td>Email</td><td>' . htmlspecialchars($row['email']) . '</td></tr>';
$html .= '<tr><td>Parent Name</td><td>' . htmlspecialchars($row['parent_name']) . '</td></tr>';
$html .= '<tr><td>Occupation</td><td>' . htmlspecialchars($row['occupation']) . '</td></tr>';
$html .= '<tr><td>Official Address</td><td>' . htmlspecialchars($row['official_address']) . '</td></tr>';
$html .= '<tr><td>Home Address</td><td>' . htmlspecialchars($row['home_address']) . '</td></tr>';
$html .= '<tr><td>Office Contact</td><td>' . htmlspecialchars($row['office_contact']) . '</td></tr>';
$html .= '<tr><td>Office Mobile</td><td>' . htmlspecialchars($row['office_mobile']) . '</td></tr>';
$html .= '<tr><td>Sibling 1 Name</td><td>' . htmlspecialchars($row['sibling_1_name']) . '</td></tr>';
$html .= '<tr><td>Sibling 1 Grade</td><td>' . htmlspecialchars($row['sibling_1_grade']) . '</td></tr>';
$html .= '<tr><td>Sibling 2 Name</td><td>' . htmlspecialchars($row['sibling_2_name']) . '</td></tr>';
$html .= '<tr><td>Sibling 2 Grade</td><td>' . htmlspecialchars($row['sibling_2_grade']) . '</td></tr>';
$html .= '<tr><td>Sibling 3 Name</td><td>' . htmlspecialchars($row['sibling_3_name']) . '</td></tr>';
$html .= '<tr><td>Sibling 3 Grade</td><td>' . htmlspecialchars($row['sibling_3_grade']) . '</td></tr>';
$html .= '<tr><td>Extracurricular</td><td>' . htmlspecialchars($row['extracurricular']) . '</td></tr>';
$html .= '<tr><td>Shortest Distance</td><td>' . htmlspecialchars($row['shortest_distance']) . '</td></tr>';
$html .= '<tr><td>Gramasevaka Division</td><td>' . htmlspecialchars($row['gramasevaka_division']) . '</td></tr>';
$html .= '<tr><td>Mode of Transport</td><td>' . htmlspecialchars($row['mode_of_transport']) . '</td></tr>';
$html .= '</table><br>';

// Add images outside the table with <br> tags and margin styling for spacing
$html .= '<div class="image-section">';
$html .= '<h2>Passport Size Photo</h2>';
$html .= '<img src="data:image/jpeg;base64,' . $passportPhotoBase64 . '"><br>';
$html .= '</div>';
$html .= '<div class="image-section">';
$html .= '<h2>Birth Certificate Copy</h2>';
$html .= '<img src="data:image/jpeg;base64,' . $birthCertificateBase64 . '"><br>';
$html .= '</div>';
$html .= '<div class="image-section">';
$html .= '<h2>Parents NIC Copy</h2>';
$html .= '<img src="data:image/jpeg;base64,' . $parentsNicBase64 . '"><br>';
$html .= '</div>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output("admission_record_{$id}.pdf", 'I');
?>
