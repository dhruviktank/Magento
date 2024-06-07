<?php

class Ccc_Outlook_Model_Api
{
    protected $_config = null;

    protected $_email = null;

    public function setUserConfig(Ccc_Outlook_Model_Configuration $userConfig)
    {
        $this->_config = $userConfig;
        return $this;
    }
    public function getUserConfig()
    {
        return $this->_config;
    }

    public function setEmail(Ccc_Outlook_Model_Email $email){
        $this->_email = $email;
        return $this;
    }

    public function getEmail(){
        return $this->_email;
    }

    public function getAuthorizationUrl()
    {
        $scope = 'https://graph.microsoft.com/Mail.Read offline_access';
        $redirectUri = Mage::getUrl('outlook/callback/token', ['id' => $this->getUserConfig()->getId()]);
        $authorizationEndpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";
        $authUrl = "$authorizationEndpoint?client_id={$this->getClientId()}&response_type=code&redirect_uri={$redirectUri}&scope={$scope}&response_mode=query";
        return $authUrl;
    }
    public function fetchAttachments(){
        $accessToken = $this->getEmail()->getUserConfig()->getAccessToken();
        $url = sprintf("https://graph.microsoft.com/v1.0/me/messages/%s/attachments", $this->getEmail()->getEmailKey());

        $client = new Zend_Http_Client($url);
        $client->setMethod(Zend_Http_Client::GET);
        $client->setHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ]);
        $response = $client->request();
        $attachments = json_decode($response->getBody(), true);
        print_r($attachments);
        return $attachments['value'];
    }
    public function getEmails()
    {
        $lastFetchedTimestamp = $this->getUserConfig()->getLastEmailTimestamp();
        $url = sprintf(
            "https://graph.microsoft.com/v1.0/me/messages?\$orderby=ReceivedDateTime%s",
            $lastFetchedTimestamp ? "&\$filter=" . urlencode("ReceivedDateTime gt " . $lastFetchedTimestamp) : ""
        );
        $headers = [
            'Authorization: Bearer ' . $this->getUserConfig()->getAccessToken(),
            'Accept: application/json'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        try {
            if (isset($response['error'])) {
                if ($response['error']['code'] == 'InvalidAuthenticationToken') {
                    $this->refreshAccessToken();
                } else {
                    throw new Exception("Error: " . $response['error']['message']);
                }
            }
            if (isset($response['value'])) {
                $emails = [];
                foreach ($response['value'] as $_email) {
                    $recipientAddress = [];
                    foreach ($_email['toRecipients'] as $_recipient) {
                        $recipientAddress[] = $_recipient['emailAddress']['address'];
                    }
                    $emails[] = [
                        'email_key' => $_email['id'],
                        'subject' => $_email['subject'],
                        'sender_name' => $_email['sender']['emailAddress']['name'],
                        'sender_address' => $_email['sender']['emailAddress']['address'],
                        'recipient_address' => implode(' ', $recipientAddress),
                        'received_at' => $_email['receivedDateTime'],
                        'body' => $_email['bodyPreview'],
                        'has_attachment' => ($_email['hasAttachments'] == false) ? 0 : 1
                    ];
                    $lastEmailTimestamp = $_email['receivedDateTime'];
                }
                return ['emails' => $emails, 'last_email_timestamp' => $lastEmailTimestamp];
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAccessToken($authorizationCode)
    {
        try {
            $tokenEndpoint = $this->getTokenEndPoint();

            $data = [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'code' => $authorizationCode,
                'redirect_uri' => $this->getRedirectUri(),
                'grant_type' => 'authorization_code',
                'scope' => 'https://graph.microsoft.com/Mail.Read offline_access'
            ];

            $curl = curl_init($tokenEndpoint);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            if ($response === false) {
                throw new Exception('Error fetching access token: ' . curl_error($curl));
            }
            curl_close($curl);

            $result = json_decode($response, true);
            if (isset($result['error'])) {
                throw new Exception('Error in response: ' . $result['error_description']);
            }
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
            return false;
        }
    }

    private function refreshAccessToken()
    {
        $refreshToken = $this->getUserConfig()->getRefreshToken();
        $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

        // Prepare the data for the POST request
        $postData = http_build_query([
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
            'scope' => 'https://graph.microsoft.com/.default'
        ]);

        // Initialize cURL
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        // Execute the POST request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            throw new Exception('HTTP request error: ' . curl_error($curl));
        }

        // Close cURL
        curl_close($curl);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Check if the response contains the new access token
        if (isset($data['access_token'])) {
            $newAccessToken = $data['access_token'];
            // print_r($newAccessToken);
            $newRefreshToken = isset($data['refresh_token']) ? $data['refresh_token'] : $refreshToken;
            // print_r($newRefreshToken);

            // Update the access token and possibly the refresh token in your user config
            $this->getUserConfig()->setAccessToken($newAccessToken);
            $this->getUserConfig()->setRefreshToken($newRefreshToken);
            $this->getUserConfig()->save();
            return $newAccessToken;
        } else {
            throw new Exception('Failed to refresh access token');
        }
    }


    protected function getClientSecret()
    {
        return $this->getUserConfig()->getClientSecret();
    }
    protected function getClientId()
    {
        return $this->getUserConfig()->getClientId();
    }
    protected function getRedirectUri()
    {
        return "http://localhost/1SBMagento/index.php/outlook/callback/token/id/{$this->getUserConfig()->getId()}/";
    }
    protected function getTokenEndPoint()
    {
        return "https://login.microsoftonline.com/common/oauth2/v2.0/token";
    }
}
