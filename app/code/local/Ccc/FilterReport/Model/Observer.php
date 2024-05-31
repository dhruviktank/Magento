<?php

class Ccc_FilterReport_Model_Observer
{
    public function updateSoldCount(Varien_Event_Observer $observer){
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();

        foreach ($items as $_item) {
            $product = Mage::getModel('catalog/product')->load($_item->getProductId());
            $currentSoldCount = $product->getData('sold_count');
            if (!$currentSoldCount) {
                $currentSoldCount = 0;
            }
            Mage::log("Current Sold Count: " . $currentSoldCount, null, 'filter-report.log');
            $newSoldCount = $currentSoldCount + $_item->getQtyOrdered();
            $product->setData('sold_count', $newSoldCount);
            try {
                $product->getResource()->saveAttribute($product, 'sold_count');
                Mage::log("Updated Sold Count: " . $newSoldCount, null, 'filter-report.log');
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
    public function decrOrderSoldCount(Varien_Event_Observer $observer){
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();

        foreach ($items as $_item) {
            $product = Mage::getModel('catalog/product')->load($_item->getProductId());
            $currentSoldCount = $product->getData('sold_count');
            if (!$currentSoldCount) {
                $currentSoldCount = 0;
            }
            Mage::log("Current Sold Count: " . $currentSoldCount, null, 'filter-report.log');
            $newSoldCount = $currentSoldCount - $_item->getQtyOrdered();
            $product->setData('sold_count', $newSoldCount);
            try {
                $product->getResource()->saveAttribute($product, 'sold_count');
                Mage::log("Updated Sold Count: " . $newSoldCount, null, 'filter-report.log');
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
    public function decrItemSoldCount(Varien_Event_Observer $observer){
        
    }
}