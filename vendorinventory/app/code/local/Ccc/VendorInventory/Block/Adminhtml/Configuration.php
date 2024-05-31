<?php

class Ccc_VendorInventory_Block_Adminhtml_Configuration extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'vendorinventory';
        $this->_controller = 'adminhtml_configuration';
        $this->_headerText = 'Configuration';
        $this->setUseAjax(true);
        $this->_removeButton('add');
        // $this->
    }
}