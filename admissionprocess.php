<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'assets/dbh/connector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $last_school = mysqli_real_escape_string($conn, $_POST['last_school']);
    $last_grade = mysqli_real_escape_string($conn, $_POST['last_grade']);
    $admission_class = mysqli_real_escape_string($conn, $_POST['admission_class']);
    $fixed_line = mysqli_real_escape_string($conn, $_POST['fixed_line']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $parent_name = mysqli_real_escape_string($conn, $_POST['parent_name']);
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);
    $official_address = mysqli_real_escape_string($conn, $_POST['official_address']);
    $home_address = mysqli_real_escape_string($conn, $_POST['home_address']);
    $office_contact = mysqli_real_escape_string($conn, $_POST['office_contact']);
    $office_mobile = mysqli_real_escape_string($conn, $_POST['office_mobile']);
    $sibling_1_name = mysqli_real_escape_string($conn, $_POST['sibling_1_name']);
    $sibling_1_grade = mysqli_real_escape_string($conn, $_POST['sibling_1_grade']);
    $sibling_2_name = mysqli_real_escape_string($conn, $_POST['sibling_2_name']);
    $sibling_2_grade = mysqli_real_escape_string($conn, $_POST['sibling_2_grade']);
    $sibling_3_name = mysqli_real_escape_string($conn, $_POST['sibling_3_name']);
    $sibling_3_grade = mysqli_real_escape_string($conn, $_POST['sibling_3_grade']);
    $extracurricular = mysqli_real_escape_string($conn, $_POST['extracurricular']);
    $shortest_distance = mysqli_real_escape_string($conn, $_POST['shortest_distance']);
    $gramasevaka_division = mysqli_real_escape_string($conn, $_POST['gramasevaka_division']);
    $mode_of_transport = mysqli_real_escape_string($conn, $_POST['mode_of_transport']);

    // Validate numeric fields
    if (!is_numeric($fixed_line) || !is_numeric($mobile) || !is_numeric($office_contact) || !is_numeric($office_mobile)) {
        echo "Error: Phone numbers must be numeric.";
        exit();
    }

    // Validate sex field
    if ($sex !== 'Male' && $sex !== 'Female') {
        echo "Error: Sex must be either Male or Female.";
        exit();
    }

    // Handle file uploads
    $passport_size_photo = $_FILES['passport_size_photo']['name'];
    $passport_tmp = $_FILES['passport_size_photo']['tmp_name'];
    $birth_certificate_copy = $_FILES['birth_certificate_copy']['name'];
    $birth_certificate_tmp = $_FILES['birth_certificate_copy']['tmp_name'];
    $parents_nic_copy = $_FILES['parents_nic_copy']['name'];
    $parents_nic_tmp = $_FILES['parents_nic_copy']['tmp_name'];

    $upload_dir = "main_uploads/requestadmission/";
    $passport_path = $upload_dir . basename($passport_size_photo);
    $birth_certificate_path = $upload_dir . basename($birth_certificate_copy);
    $parents_nic_path = $upload_dir . basename($parents_nic_copy);

    // Move uploaded files to the upload directory
    if (!move_uploaded_file($passport_tmp, $passport_path)) {
        die('Failed to upload passport size photo.');
    }
    if (!move_uploaded_file($birth_certificate_tmp, $birth_certificate_path)) {
        die('Failed to upload birth certificate copy.');
    }
    if (!move_uploaded_file($parents_nic_tmp, $parents_nic_path)) {
        die('Failed to upload parents NIC copy.');
    }

    // SQL query to insert data into the table
    $sql = "INSERT INTO request_admission (student_name, dob, nationality, religion, sex, last_school, last_grade, admission_class, fixed_line, mobile, email, parent_name, occupation, official_address, home_address, office_contact, office_mobile, sibling_1_name, sibling_1_grade, sibling_2_name, sibling_2_grade, sibling_3_name, sibling_3_grade, extracurricular, shortest_distance, gramasevaka_division, mode_of_transport, passport_size_photo, birth_certificate_copy, parents_nic_copy)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssssssssssss",
        $student_name, $dob, $nationality, $religion, $sex, $last_school, $last_grade, $admission_class, $fixed_line, $mobile, $email, $parent_name, $occupation, $official_address, $home_address, $office_contact, $office_mobile, $sibling_1_name, $sibling_1_grade, $sibling_2_name, $sibling_2_grade, $sibling_3_name, $sibling_3_grade, $extracurricular, $shortest_distance, $gramasevaka_division, $mode_of_transport, $passport_path, $birth_certificate_path, $parents_nic_path);
    
    if ($stmt->execute()) {
        echo "
        <div class='success-message'>
            <div class='success-circle'>
                <span class='tick-mark'>&#10004;</span>
            </div>
            <p>Admission Applied Successfully, You will be notified shortly.</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'admission.php';
            }, 5000);
        </script>
        ";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<style>
    body {
    background-color: #000223; /* Changed to blue */
    }
    .success-message {
        text-align: center;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 20px;
        border-radius: 10px;
        margin: 50px auto;
        width: 50%;
    }
    .success-circle {
        width: 50px;
        height: 50px;
        background-color: #28a745;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 10px;
    }
    .tick-mark {
        color: white;
        font-size: 24px;
    }
</style>
