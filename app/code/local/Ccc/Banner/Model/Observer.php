<?php

class Ccc_Banner_Model_Observer{
    public function dispatchCustomEvent(Varien_Event_Observer $observer){
        $order = $observer->getEvent()->getOrder();
        Mage::dispatchEvent('custom_order_event', array('order' => $order));
    }

    public function handleCustomEvent($observer)
    {
        $order = $observer->getEvent()->getOrder();
        // Implement your custom logic here
        Mage::log('Custom event triggered for order ID: ' . $order->getId(), null, 'custommodule.log');
    }
    public function bannerUpdated($observer)
    {
        $val = Mage::getStoreConfig('banner/general/banner_enabled');
        Mage::getConfig()->saveConfig('advanced/modules_disable_output/Ccc_Banner', $val);
    }
    public function advancedUpdated($observer)
    {
        $val = Mage::getStoreConfig('advanced/modules_disable_output/Ccc_Banner');
        Mage::getConfig()->saveConfig('banner/general/banner_enabled', $val);
    }
}