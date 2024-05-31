<?php

class Ccc_Salesman_Block_Adminhtml_Bonus_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('bonus_configuration_tabs');
        $this->setDestElementId('add_form');
        $this->setTitle(Mage::helper('salesman')->__('Bonus Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('config', array(
            'label' => 'config',
            'title' => 'config',
            'url' => $this->getUrl('*/*/configuration', array('_current' => true)),
            'active' => Mage::registry('bonus_configuration') ? true : false,
            )
        );
        $this->addTab('league', array(
            'label' => 'league',
            'title' => 'league',
            'url' => $this->getUrl('*/*/league', array('_current' => true)),
            'active' => Mage::registry('bonus_league') ? true : false,
        )
        );
        return parent::_beforeToHtml();
    }
}