<?php

class Ccc_Salesman_Block_Adminhtml_Metric extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_metric';
        $this->_blockGroup = 'salesman';
        $this->_headerText = Mage::helper('salesman')->__('Salesman Metric');
        $this->_addButtonLabel = Mage::helper('salesman')->__('Add New Metric');
        parent::__construct();
    }
}