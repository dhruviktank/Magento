<?php

class Ccc_Repricer_Block_Adminhtml_Matching extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_matching';
        $this->_blockGroup = 'repricer';
        $this->_headerText = Mage::helper('repricer')->__('Manage Repricer');
        parent::__construct();
    }
    public function _prepareLayout()
    {
        $this->_removeButton('add');
        $this->addButton(
            'enable_mass_update',
            [
                'label' => Mage::helper('repricer')->__('Enable Mass Action'),
                'class' => 'enable_mass_update',
                'value' => 0
            ]
        );
        return parent::_prepareLayout();
    }
}