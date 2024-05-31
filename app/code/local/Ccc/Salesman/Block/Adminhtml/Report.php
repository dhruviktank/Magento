<?php

class Ccc_Salesman_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_report';
        $this->_blockGroup = 'salesman';
        $this->_headerText = Mage::helper('salesman')->__('Salesman Report');
        parent::__construct();
        $this->_removeButton('add');
    }
}