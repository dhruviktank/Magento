<?php

require_once('../app/Mage.php');

Mage::app();

// Mage::getModel('outlook/observer')->getAccessToken();
// Mage::getModel('outlook/observer')->getEmails();
// Mage::getModel('vendorinventory/observer')->applyRule();
$observer = new Ccc_Outlook_Model_Observer();

$observer->fetchUsers();
// $observer->getEmails();
// // Step 1: Redirect the user to the authorization URL
// $authorizationUrl = $observer->getAuthorizationUrl();
// print_r($authorizationUrl);
// // Redirect the user to $authorizationUrl to authenticate and consent
// header('Location: '.$authorizationUrl);
// // Step 2: After successful authentication and consent, extract the authorization code from the redirect URL query parameters
// if(isset($_GET['code'])) {
//     $authorizationCode = $_GET['code'];
//     echo $authorizationCode;
//     // Now you have the authorization code, you can proceed with obtaining the access token
//     // For example:
//     // $accessToken = $observer->getAccessToken($authorizationCode);
// } else {
//     // Handle case where authorization code is not present
//     echo "Authorization code not found.";
// }

// Step 3: Call the getEmailsUsingAuthorizationCode method with the obtained authorization code
// $emails = $observer->getEmailsUsingAuthorizationCode($authorizationCode);

// Step 4: Handle the response
// For example, print the retrieved emails
// echo json_encode($emails);
