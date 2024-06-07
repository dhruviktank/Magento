<?php

class Ccc_Outlook_Model_Observer
{
    public function fetchUsers()
    {
        $userConfigurations = Mage::getModel('outlook/configuration')->getCollection();
        foreach ($userConfigurations as $_userConfig) {
            $_userConfig->fetchEmails();
        }
    }

    public function outlookEmailSave(Varien_Event_Observer $observer){
        Mage::log($observer->getEvent()->getEmail()->getData(), null, 'event.log', true);
        Mage::log('save event dispatched', null, 'event.log', true);
    }
    public function outlookEmailRemove(Varien_Event_Observer $observer){
        Mage::log($observer->getEvent()->getEmail()->getData(), null, 'event.log', true);
        Mage::log('remove event dispatched', null, 'event.log', true);
    }
}