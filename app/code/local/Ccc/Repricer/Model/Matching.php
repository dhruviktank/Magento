<?php

class Ccc_Repricer_Model_Matching extends Mage_Core_Model_Abstract{
    // const REASON_DEFAULT = 0;
    protected function _construct(){
        $this->_init('repricer/matching');
    }
    public function getCompArray()
    {
        $compIds = Mage::getModel('repricer/competitor')->getCollection()->getAllIds();
        $compName = Mage::getModel('repricer/competitor')->getCollection()->getColumnValues('name');
        $compName=array_combine($compIds, $compName);
        return $compName;
    }
}