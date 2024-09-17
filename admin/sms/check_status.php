<?php
// Include the eSMS API library
require_once('../../PHP-SMS-API-lib/send_sms_impl.php');
require_once('../../PHP-SMS-API-lib/dto/token_response.php');

// Read the saved token
$token = file_get_contents('token.txt');

// Create an object of SendSMSImpl
$sendSmsImpl = new SendSMSImpl();

// Create an object of TransactionBody
$transactionBody = new TransactionBody();

// Set the transaction ID
$transactionBody->setTransactionId('unique_transaction_id');

// Check the campaign status
$statusResponse = $sendSmsImpl->getTransactionIDStatus($transactionBody, $token);

// Check the status response
if ($statusResponse->getStatus() == 'success') {
    echo "Campaign Status: " . $statusResponse->getData()->getCampaignStatus();
} else {
    echo "Failed to check status: " . $statusResponse->getComment();
}
?>
