<?php

class Ccc_Outlook_Model_Resource_Email extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct(){
        $this->_init("outlook/email", "email_id");
    }
}