<?php

class Ccc_Repricer_Helper_Data extends Mage_Core_Helper_Abstract
{

    const REPRICER_MATCHING_REASON_DEFAULT_NOMATCH = 1;
    const REPRICER_MATCHING_REASON_ACTIVE = 2;
    const REPRICER_MATCHING_REASON_OUT_OF_STOCK = 3;
    const REPRICER_MATCHING_REASON_NOT_AVAILABLE = 4;
    const REPRICER_MATCHING_REASON_WRONG_MATCH = 5;

    public function getReasonOptionArray()
    {
        return array(
           self::REPRICER_MATCHING_REASON_DEFAULT_NOMATCH => $this->__('No Match'),
           self::REPRICER_MATCHING_REASON_ACTIVE => $this->__('Active'),
           self::REPRICER_MATCHING_REASON_OUT_OF_STOCK => $this->__('Out of Stock'),
           self::REPRICER_MATCHING_REASON_NOT_AVAILABLE => $this->__('Not Available'),
           self::REPRICER_MATCHING_REASON_WRONG_MATCH => $this->__('Wrong Match'),
        );
    }
}