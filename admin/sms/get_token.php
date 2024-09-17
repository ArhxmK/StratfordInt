<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test writing to a file
$testResult = file_put_contents('test_write.txt', 'This is a test.');
if ($testResult === false) {
    die('Failed to write to test_write.txt');
} else {
    echo 'test_write.txt written successfully.<br>';
}

// Include the eSMS API library
require_once('../../PHP-SMS-API-lib/send_sms_impl.php');

echo "Starting token retrieval process...<br>";

// Your existing code for getting a token
$sendSmsImpl = new SendSMSImpl();
$tokenBody = new TokenBody();
$tokenBody->setUsername('');
$tokenBody->setPassword('');

$tokenResponse = $sendSmsImpl->getToken($tokenBody);

echo "Token response status: " . $tokenResponse->getStatus() . "<br>";

if ($tokenResponse->getStatus() == 'success') {
    $token = $tokenResponse->getToken();
    echo "Token retrieved: " . htmlspecialchars($token) . "<br>";
    $result = file_put_contents('token.txt', $token);
    if ($result === false) {
        echo "Failed to write token to file.<br>";
    } else {
        echo "Token written to file successfully.<br>";
    }
    echo "Token retrieved and saved successfully.";
} else {
    echo "Failed to retrieve token: " . $tokenResponse->getComment();
}
?>
