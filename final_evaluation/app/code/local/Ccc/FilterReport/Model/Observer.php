<?php

class Ccc_FilterReport_Model_Observer
{
    public function updateSoldCount(Varien_Event_Observer $observer){
        $order = $observer->getEvent()->getOrder();
        // print_r($order);
        Mage::log('called', null, 'filter-report.log');
        $items = $order->getAllItems();

        foreach($items as $_item){
            $product = $_item->getProduct();
            $currentSoldCount = $product->getAttribute('sold_count');
            if(!$currentSoldCount){
                $currentSoldCount = 0;
            }
            $newSoldCount = $currentSoldCount + $_item->getQtyOrdered();
            $product->setData('sold_count', $newSoldCount)->getResource()->saveAttribute($product, 'sold_count');
        }
    }
}