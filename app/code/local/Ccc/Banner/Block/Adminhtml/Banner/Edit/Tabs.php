<?php

class Ccc_Banner_Block_Adminhtml_Banner_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('banner')->__('Banner Form Tabs'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('tab_first', array(
            'label' => Mage::helper('practicetest')->__('General Information'),
            'title' => Mage::helper('practicetest')->__('General Information'),
            'content' => $this->getLayout()->createBlock('banner/adminhtml_banner_edit_tab_form')->toHtml()
        ));

        $this->addTab('display_section', array(
            'label' => Mage::helper('practicetest')->__('Image'),
            'content' => $this->getLayout()->createBlock('banner/adminhtml_banner_edit_tab_image')->toHtml()
        ));

        
        return parent::_beforeToHtml();
    }
}