<?php

class Ccc_FilterReport_Model_System_Config_Source_Idletime{
    public function toOptionArray()
    {
        return array(
            array('value' => 5, 'label'=>Mage::helper('adminhtml')->__('5 Minute')),
            array('value' => 10, 'label'=>Mage::helper('adminhtml')->__('10 Minute')),
            array('value' => 30, 'label'=>Mage::helper('adminhtml')->__('30 Minute')),
            array('value' => 60, 'label'=>Mage::helper('adminhtml')->__('60 Minute')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('adminhtml')->__('No'),
            1 => Mage::helper('adminhtml')->__('Yes'),
        );
    }
}