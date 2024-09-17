<?php
require "../admin/dbh/connector.php";

// Check if the ID parameter is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        echo "Admission Request ID: " . $id . "<br>";
    } else {
        die("Invalid ID parameter provided.");
    }
} else {
    die("No ID parameter provided.");
}

// Verify the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database.<br>";

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

// Directly output the query and result
echo "SQL Query: " . $query . "<br>";
echo "SQL Query Result: " . print_r($row, true) . "<br>";

if (!$row) {
    die("Record not found.");
}

// Function to convert image to base64
function convertImageToBase64($imagePath) {
    echo "Checking image path: " . $imagePath . "<br>";
    if (file_exists($imagePath)) {
        echo "Image file exists: " . $imagePath . "<br>";
        if (is_readable($imagePath)) {
            echo "Image file is readable: " . $imagePath . "<br>";
            $imageData = file_get_contents($imagePath);
            if ($imageData !== false) {
                return 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
            } else {
                echo "Failed to read image file: " . $imagePath . "<br>";
                return '';
            }
        } else {
            echo "Image file is not readable: " . $imagePath . "<br>";
            return '';
        }
    } else {
        echo "Image file does not exist: " . $imagePath . "<br>";
        return '';
    }
}

// Generate base64 images using paths directly from the database
$passportPhotoPath = "../" . $row['passport_size_photo'];
$birthCertificatePath = "../" . $row['birth_certificate_copy'];
$parentsNicPath = "../" . $row['parents_nic_copy'];

// Check and display paths
echo "Passport Photo Path: " . $passportPhotoPath . "<br>";
echo "Birth Certificate Path: " . $birthCertificatePath . "<br>";
echo "Parents NIC Path: " . $parentsNicPath . "<br>";

$passportPhotoBase64 = convertImageToBase64($passportPhotoPath);
$birthCertificateBase64 = convertImageToBase64($birthCertificatePath);
$parentsNicBase64 = convertImageToBase64($parentsNicPath);

// Display base64 strings
echo "Passport Photo Base64: " . substr($passportPhotoBase64, 0, 100) . "...<br>";
echo "Birth Certificate Base64: " . substr($birthCertificateBase64, 0, 100) . "...<br>";
echo "Parents NIC Base64: " . substr($parentsNicBase64, 0, 100) . "...<br>";
?>
