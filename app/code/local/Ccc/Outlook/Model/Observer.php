<?php

class Ccc_Outlook_Model_Observer
{
    // protected $tenantId = 'f8cdef31-a31e-4b4a-93e4-5f571e91255a';
    protected $tenantId = '17e006e0-2759-4edb-9c39-96d401a6118f';
    // protected $clientId = 'e3ae518c-ab38-4549-ab58-eeec87126e77';
    protected $clientId = '23b00d4d-86c1-466e-8bca-554a025123a6';
    // protected $client = 'YsQ8Q~bw36LKC3ThG.hJgwqsQWyEYtoAvyfyFbrn';
    //64S8Q~xHpmxFf7Tk1IN6ySu.2YQm.voaXALVBakD

    //YsQ8Q~bw36LKC3ThG.hJgwqsQWyEYtoAvyfyFbrn

    // iTM8Q~uVnEfgCVxcknhPCg2p7~Kz02rP.HB3PaUP
    protected $scope = 'https://graph.microsoft.com/.default';
    protected $grantType = 'client_credentials';
    public function getClientAccessToken()
    {
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = [
            'client_id' => $this->clientId,
            'scope' => $this->scope,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching access token: ' . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['error'])) {
            throw new Exception('Error in response: ' . $result['error_description']);
        }
        // print_r($result['access_token']); return;

        return $result['access_token'];
    }
    public function getAccessToken($authorizationCode)
    {
        $tokenEndpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authorizationCode,
            'redirect_uri' => 'http://localhost/1SBMagento/index.php/outlook/callback/token/id/1/',
            'grant_type' => 'authorization_code',
            'scope' => 'https://graph.microsoft.com/Mail.Read'
        ];

        $ch = curl_init($tokenEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching access token: ' . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['error'])) {
            throw new Exception('Error in response: ' . $result['error_description']);
        }
        print_r($result['access_token']);

        return $result['access_token'];
    }

    public function getAuthorizationUrl()
    {
        $scope = 'https://graph.microsoft.com/Mail.Read';
        $redirectUri = 'http://localhost/1SBMagento/root-script/script1.php';
        $authorizationEndpoint = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/authorize";
        $authUrl = "$authorizationEndpoint?client_id={$this->clientId}&response_type=code&redirect_uri=$redirectUri&scope={$scope}";
        return $authUrl;
    }

    // Method to get emails
    public function getEmails()
    {
        // $accessToken = 'EwBwA8l6BAAUbDba3x2OMJElkF7gJ4z/VbCPEz0AAU+DNMG8oe8x79HiGDw+AIPr69yCGfuZlYZYwNC7OeBWsrKYXw710cSHDaCM/zzROPdyaUy9zDGpQDzyw3rp706yg7H9iYXaGiQ8kBlHnJRhZI+iSTtYetFhW0+at5sqtFlm8qOl72hIY4x/eQuy3+b8Op6rjxiLrU/a59zqESUP38TqHrzPZU9ji9y5XQB5TalEjFBPVmkcdz0z3AwgGEvsscFO1jKA3f7tQdYaMk5+xWtV0B1k7MkM1Rts9zu67XV61RUvIe058BqAKrPvBPoJYcO5/1SRR/Q4DbeF/z/OnYl3aNPZgM9vfoXsRvZByyYVRDAzSqML+CpzRZb5pXcDZgAACJuML+0GD3msQAJXE2yIJIFj/EKeEJlclNd8FJq2F0/bHwfwBeTjSlzhh0n/rBOw2wL85/VY39xsGNtkDQ1w2qeduqy6JkB9gddHkivpqWYg09XzcV5zHXKQZ38/RZYb+I1mxDqc0AzvGp9YFUthLxq5Ktg/dsr145A/Ve4XDGrUcOSMOI1Xxj3mL+ZbzM0y136+PizUVBMT/HnV79UC4hCvdTSWkTSzJKeLhIcqfy/+JMgsAJ1YZiE74LYgLlNOK0nx99uy4U3TNHfVoOSuJWyjxjk1HqiSSOSfpedbvEyK+L2DkQqzYviUFRUK5mqgIXLi0FSmeuTfoSTsX+B0ZmCMaGYPyhdNy2aqas6IWwAnukx32u0gnnJl/rOUzqo8o41ls72hC1vNayqxYFqErJYCKZFLu/Ee2J4phggEGzApXkmVls28IQXxKvNN7xBWoiwxefqLh9o8spTN8U3SBwGYb6KIpb99LLfvKu+6aRPvDLfCS4V116twWidXWPhDspvbrMjOqgQfBVQqB+gAG9H6HL0BtwVVoi7IpYTxDjtNBV/ugepvwPgAOIlnR4IbdbgANRzCA2BMG8o2V0lMYbfPsvnV2qEa6a7idKamroBWbHXowgxCjx1WhikEgie4KgGfI365NIyFG8LTZTSP/Nv2f6eiYtGj1ub7++KAZwQwYf1XTIZj6NFe0EYFbDHKIhrK57NEZX3w/FqBaq7AmMohNep1QM21Siv5Ebb5eREREPKGs4FknIt80zUNEwkmmeV5uWm2l+qIyvWCAg==';
        $accessToken = $this->getClientAccessToken();
        // $userId = "ccdhruvik@outlook.com";
        $url = "https://graph.microsoft.com/v1.0/users";
        // $url = "https://graph.microsoft.com/v1.0/users/d18b69bc-2db4-4be0-9e8b-f3551bced1ea/messages"; 
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
            'grant_type: authorization_code',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        print_r($response);
        return json_decode($response, true);
    }
    public function getEmailsUsingAuthorizationCode($authorizationCode)
    {
        $accessToken = $this->getAccessToken($authorizationCode);
        $url = "https://graph.microsoft.com/v1.0/me/messages"; // Change this to the correct endpoint for retrieving emails
        $headers = [
            'Authorization: Bearer ' . $accessToken,
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


    // observer code starts from here. above code moved to API model.............

    public function fetchUsers()
    {
        $userConfigurations = Mage::getModel('outlook/configuration')->getCollection()->getItems();

        foreach ($userConfigurations as $_userConfig) {
            $_userConfig->fetchEmails();
        }
    }


}