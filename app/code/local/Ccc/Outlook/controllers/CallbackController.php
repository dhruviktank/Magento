<?php

class Ccc_Outlook_CallbackController extends Mage_Core_Controller_Front_Action{
    public function tokenAction() {
        $identifier = $this->getRequest()->getParam('id');
        $code = $_GET['code'];
        $accessToken = Mage::getModel('outlook/observer')->getAccessToken($code);

        $config = Mage::getModel('outlook/configuration')->load($identifier);
        $config->setAccessToken($accessToken);
        $config->save();
        print_r("Thank You");
    
    }
}