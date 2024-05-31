<?php

class Ccc_Repricer_Model_Matching extends Mage_Core_Model_Abstract{
    // const REASON_DEFAULT = 0;
    public const CONST_REASON_NO_MATCH = 1;
    public const CONST_REASON_ACTIVE = 2;
    public const CONST_REASON_OUT_OF_STOCK = 3;
    public const CONST_REASON_NOT_AVAILABLE = 4;
    public const CONST_REASON_WRONG_MATCH = 5;
    protected function _construct(){
        $this->_init('repricer/matching');
    }
    public function getReasonOptionArray()
    {
        $arr = array(
            self::CONST_REASON_NO_MATCH => Mage::helper('repricer')->__('No Match'),
            self::CONST_REASON_ACTIVE => Mage::helper('repricer')->__('Active'),
            self::CONST_REASON_OUT_OF_STOCK => Mage::helper('repricer')->__('Out of Stock'),
            self::CONST_REASON_NOT_AVAILABLE => Mage::helper('repricer')->__('Not Available'),
            self::CONST_REASON_WRONG_MATCH => Mage::helper('repricer')->__('Wrong Match'),
        );
        return $arr;
    }
}