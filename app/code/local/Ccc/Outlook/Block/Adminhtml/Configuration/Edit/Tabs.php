<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('outlook')->__('Configuration Form Tabs'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label' => Mage::helper('outlook')->__('General'),
            'title' => Mage::helper('outlook')->__('General'),
            'content' => $this->getLayout()->createBlock('outlook/adminhtml_configuration_edit_tab_general')->toHtml()
        ));

        $this->addTab('event', array(
            'label' => Mage::helper('outlook')->__('Event'),
            'content' => $this->getLayout()->createBlock('outlook/adminhtml_configuration_edit_tab_event')->toHtml()
        ));

        
        return parent::_beforeToHtml();
    }
}