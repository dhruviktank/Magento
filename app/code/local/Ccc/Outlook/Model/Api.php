<?php

class Ccc_Outlook_Model_Api
{
    protected $_config = null;
    protected $client;
    private $tenantId = '17e006e0-2759-4edb-9c39-96d401a6118f';
    private $clientId = '23b00d4d-86c1-466e-8bca-554a025123a6';
    private $client = 'YsQ8Q~bw36LKC3ThG.hJgwqsQWyEYtoAvyfyFbrn';
    public function __construct($tenantId = null, $clientId = null, $clientSecret = null)
    {
        $this->client = new Zend_Http_Client();
    }

    public function setUserConfig($userConfig){
        $this->_config = $userConfig;
        return $this;
    }
    public function getUserConfig(){
        return $this->_config;
    }
    public function getEmails(){
        $url = "https://graph.microsoft.com/v1.0/me/messages"; // Change this to the correct endpoint for retrieving emails
        $headers = [
            'Authorization: Bearer ' . $this->getUserConfig()->getAccessToken(),
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
