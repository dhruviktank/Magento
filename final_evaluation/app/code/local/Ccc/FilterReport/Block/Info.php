<?php

class Ccc_FilterReport_Block_Info extends Mage_Catalog_Block_Product_Abstract
{
    public function getSoldQty()
    {
        $product = $this->getProduct();
        $soldQty = $product->getSoldCount();
        if ($soldQty) {
            return $soldQty;
        } else {
            return 0;
        }
    }
}