<?php

class Ccc_Outlook_CallbackController extends Mage_Core_Controller_Front_Action
{
    public function tokenAction()
    {
        $identifier = $this->getRequest()->getParam('id');
        $config = Mage::getModel('outlook/configuration')->load($identifier);
        $code = $_GET['code'];
        $accessToken = Mage::getModel('outlook/api')
            ->setUserConfig($config)
            ->getAccessToken($code);
        if ($accessToken) {
            print_r($accessToken);
            $config->setAccessToken($accessToken['access_token']);
            $config->setRefreshToken($accessToken['refresh_token']);
            $config->save();
            print_r("Token Saved Succesfully");
        }else {
            print_r("Token Not Fetched");
        }

    }
}