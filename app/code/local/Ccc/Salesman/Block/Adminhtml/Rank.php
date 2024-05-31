<?php

class Ccc_Salesman_Block_Adminhtml_Rank extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_rank';
        $this->_blockGroup = 'salesman';
        $this->_headerText = Mage::helper('salesman')->__('Salesman Rank');
        parent::__construct();
        $this->_removeButton('add');
    }
}