<?php

class Ccc_Outlook_Model_Email extends Mage_Core_Model_Abstract{
    protected $_userConfig = null;
    protected function _construct(){
        $this->_init("outlook/email");
    }

    public function setUserConfig($userConfig){
        $this->_userConfig = $userConfig;
        return $this;
    }

    public function getUserConfig(){
        return $this->_userConfig;
    }

    public function fetchAndSaveAttachment(){
        $api = Mage::getModel('outlook/api');
        $api->setEmail($this);
        $attachments = $api->fetchAttachments();
        foreach($attachments as $_attachment)
        {
            $this->setAttachment($_attachment);
            Mage::getModel('outlook/attachment')->saveAttachment($this);
        }
    }

    public function setRawData($emailData){
        $this->addData($emailData);
        $this->setConfigurationId($this->getUserConfig()->getId());
    }
    
    public function dispatchEmailEvent()
    {
        $eventData = Mage::getModel('outlook/event')->getCollection()
                    ->addFieldToFilter('configuration_id', $this->getUserConfig()->getId());
        $email = $this->getData();
        $eventGroups = [];
        foreach ($eventData->getData() as $event) {
            $group_id = $event['group_id'];
            if (!isset($eventGroups[$group_id])) {
                $eventGroups[$group_id] = [];
            }
            $eventGroups[$group_id][] = $event;
        }
        foreach ($eventGroups as $group_id => $conditions) {
            $allConditionsMet = true;
            foreach ($conditions as $_condition) {
                $condition = $_condition['condition'];
                $field = $_condition['field'];
                $value = $_condition['value'];

                switch ($field) {
                    case 'from':
                        $allConditionsMet = $this->compareValues($email['sender_address'], $value, $condition);
                        break;
                    case 'to':
                        $allConditionsMet = $this->compareValues($email['recipient_address'], $value, $condition);
                        break;
                    case 'subject':
                        $allConditionsMet = $this->compareValues($email['subject'], $value, $condition);
                        break;
                    default:
                        $allConditionsMet = false;
                        break;
                }

                if (!$allConditionsMet)
                    break;
            }
            if ($allConditionsMet) { 
                Mage::dispatchEvent('outlook_email_' . $conditions[0]['event'], array('email' => $this));
                echo "Dispatched event: {$conditions[0]['event']}<br>";
            }
        }
    }

    public function compareValues($value1, $value2, $condition){
        switch(strtolower($condition)){
            case "equals":
                return $value1 == $value2;
            case "contains":
                return (stripos($value1, $value2)!=false)?true:false;
            default:
                return false;
        }
    }
}