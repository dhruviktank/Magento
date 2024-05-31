<?php

class Ccc_Banner_Model_Modules_Enable{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Disable')),
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Enable')),
        );
    }
    public function toArray()
    {
        return array(
            1 => Mage::helper('adminhtml')->__('Disable'),
            0 => Mage::helper('adminhtml')->__('Enable'),
        );
    }
}