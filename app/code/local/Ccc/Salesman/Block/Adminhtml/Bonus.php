<?php

class Ccc_Salesman_Block_Adminhtml_Bonus extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_bonus';
        $this->_blockGroup = 'salesman';
        $this->_headerText = Mage::helper('salesman')->__('Salesmen Bonus');
        $this->_addButtonLabel = Mage::helper('salesman')->__('Add New Configuration');
        parent::__construct();
        // $this->_removeButton('add');
    }
}