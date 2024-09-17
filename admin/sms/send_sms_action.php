<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the necessary files
require_once('../../PHP-SMS-API-lib/send_sms_impl.php');

// Read the token from the token.txt file
$token = file_get_contents('token.txt');
if ($token === false) {
    die('Failed to read token from token.txt');
}

// Create an instance of SendSMSImpl
$sendSmsImpl = new SendSMSImpl();

// Generate a unique transaction ID (e.g., based on timestamp)
$uniqueTransactionId = time();  // Use the current timestamp for uniqueness

// Set the SMS details
$sendTextBody = new SendTextBody();
$sendTextBody->setSourceAddress('KanchTest');  // Replace with your desired source address
$sendTextBody->setMessage('This is a test message from Stratford Int.');
$sendTextBody->setTransactionId($uniqueTransactionId);  // Use the unique transaction ID
$sendTextBody->setMsisdn($sendSmsImpl->setMsisdns(array('0772133583')));  // Replace with the recipient's mobile number

// Send the SMS
$sendResponse = $sendSmsImpl->sendText($sendTextBody, $token);

// Debug the response
echo "<pre>";
var_dump($sendResponse);
echo "</pre>";

if ($sendResponse->getStatus() == 'success') {
    echo "SMS sent successfully.";
} else {
    echo "Failed to send SMS: " . $sendResponse->getComment();
}
?>
