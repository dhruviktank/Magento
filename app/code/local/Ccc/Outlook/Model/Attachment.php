<?php

class Ccc_Outlook_Model_Attachment extends Mage_Core_Model_Abstract{
    protected function _construct(){
        $this->_init("outlook/attachment");
    }

    public function saveAttachment($email)
    {
        $attachment = $email->getAttachment();
        if (isset($attachment['@odata.mediaContentType']) && isset($attachment['contentBytes'])) {
            $fileName = $attachment['name'];
            $mediaDir = Mage::helper('outlook')->getBasePath();
            $configDir = $mediaDir . DS . 'attachments' . DS . $email->getConfigurationId();
            if (!is_dir($configDir)) {
                mkdir($configDir, 0777, true);
            }
            $filePath = $configDir . DS . $fileName;
            file_put_contents($filePath, base64_decode($attachment['contentBytes']));

            $this->setData([
                'email_id' => $email->getId(),
                'file_path' => 'attachments' . DS . $email->getConfigurationId(),
                'file_name' => $fileName,
            ])->save();

            Mage::log('Saved attachment: ' . $fileName, null, 'emails.log');
        }
    }
}