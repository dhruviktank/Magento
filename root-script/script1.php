<?php

require_once ('../app/Mage.php');

Mage::app();

$code = $_GET['code'];
$observer = new Ccc_Outlook_Model_Observer();

print_r($observer->saveToken($code));
class Test
{
    protected $clientId = '23b00d4d-86c1-466e-8bca-554a025123a6';
    protected $clien = 'YsQ8Q~bw36LKC3ThG.hJgwqsQWyEYtoAvyfyFbrn';
    protected $accessToken;

    public function getAccessToken()
    {
        $tokenUrl = 'https://login.microsoftonline.com/17e006e0-2759-4edb-9c39-96d401a6118f/oauth2/v2.0/token'; // Replace {tenant_id} with your tenant ID

        $postData = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'https://graph.microsoft.com/.default',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if (isset($tokenData['access_token'])) {
            $this->accessToken = $tokenData['access_token'];
            // print_r($tokenData['access_token']);
            return $this->accessToken;
        } else {
            // Handle error
            return false;
        }

    }
    public function fetchEmails()
    {
        if (!$this->accessToken) {
            $this->getAccessToken();
        }

        $url = "https://graph.microsoft.com/v1.0/me/messages";

        $headers = array(
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
        );
        

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $emails = json_decode($response, true);
        print_r($emails);
        return $emails;
    }
}
// $test = new Test();
// $test->fetchEmails();

// print_r(Mage::getModel('outlook/api')->getUserDetails());