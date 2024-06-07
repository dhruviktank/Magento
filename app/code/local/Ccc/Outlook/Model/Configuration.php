<?php

class Ccc_Outlook_Model_Configuration extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init("outlook/configuration");
    }

    public function fetchEmails()
    {
        $response = Mage::getModel('outlook/api')
            ->setUserConfig($this)
            ->getEmails();
        try {
            if ($response != false) {
                foreach ($response['emails'] as $_email) {
                    $email = Mage::getModel('outlook/email');
                    $email->setUserConfig($this);
                    $email->setRawData($_email);
                    $email->dispatchEmailEvent(); die;
                    // $email->save();
                    if($email->getHasAttachment()){
                        // $email->fetchAndSaveAttachment();
                    }
                }
                $latestEmailTimestamp = new DateTime($response['last_email_timestamp']);
                $latestEmailTimestamp->modify('+1 second');
                // $this->setLastEmailTimestamp($latestEmailTimestamp->format('Y-m-d\TH:i:s\Z'));
                // $this->save();  
            }
        } catch (Exception $e) {
            echo "Error while fetching emails: " . $e->getMessage();
            return;
        }
    }
}