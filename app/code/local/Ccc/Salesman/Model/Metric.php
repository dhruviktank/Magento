<?php

class Ccc_Salesman_Model_Metric extends Mage_Core_Model_Abstract
{
    const PRODUCT_METRIC = 'Product';
    const SHIPPING_METRIC = 'Shipping';
    const TAX_METRIC = 'Tax';

    protected function _construct()
    {
        $this->_init('salesman/metric');
    }

    public function getMetrics(){
        return [
            self::PRODUCT_METRIC,
            self::SHIPPING_METRIC,
            self::TAX_METRIC
        ];   
    }
}