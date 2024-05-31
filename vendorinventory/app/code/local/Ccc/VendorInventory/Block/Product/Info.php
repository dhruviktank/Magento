<?php

class Ccc_VendorInventory_Block_Product_Info extends Mage_Catalog_Block_Product_Abstract
{
    public function getDeliveryDate()
    {
        Mage::dispatchEvent('inventory_custome_event');
        $product = $this->getProduct();
        $instockDate = $product->getInstockDate();
        if ($instockDate) {
            $instockDateTime = DateTime::createFromFormat('d/m/Y', $instockDate);
            $today = new DateTime();
            $difference = $instockDateTime->diff($today)->days;
            if ($instockDateTime <= $today) {
                return $today->modify('+2 days')->format('d-M Y');
            } elseif ($difference < 25) {
                return 'between '.$today->modify('+7 days')->format('d-M Y').' to '.$today->modify('+3 days')->format('d-M Y');
            } else {
                return 'between '.$today->modify('+15 days')->format('d-M Y').' to '.$today->modify('+5 days')->format('d-M Y');
            }
        } else {
            return 'Backorder';
        }


    }

}