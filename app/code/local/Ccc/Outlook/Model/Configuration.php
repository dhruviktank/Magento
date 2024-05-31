<?php

class Ccc_Outlook_Model_Configuration extends Mage_Core_Model_Abstract{
    protected function _construct(){
        $this->_init("outlook/configuration");
    }

    public function fetchEmails(){
        $emails = Mage::getModel('outlook/api')
            ->setUserConfig($this)
            ->getEmails();
        echo "<pre>";
        
        foreach($emails as $_email){
            $model = Mage::getModel('outlook/email');
            print_r($_email);
            // $model->setConfigurationId($this->getId());
            // $model->setSubject($_email->subject);
            // $model->setFrom($_email->from);
            // $model->setBody($_email->body);
            // $model->save();
            // $model->saveAttachments($_email->attachment);
        }
    }
}