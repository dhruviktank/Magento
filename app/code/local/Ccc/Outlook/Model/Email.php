<?php

class Ccc_Outlook_Model_Email extends Mage_Core_Model_Abstract{
    protected function _construct(){
        $this->_init("outlook/email");
    }

    public function saveAttachments($attachments){
        $attachment = Mage::getModel('outlook/attachment');
        
    }
}