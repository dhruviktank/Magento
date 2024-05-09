<?php

class Ccc_PracticeTest_Block_Adminhtml_Container_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
    public function __construct(){
        parent::__construct();
        $this->setId('practicetest_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('practicetest')->__('Tabs'));
    }

    protected function _beforeToHtml(){
        $this->addTab('tab_first', array(
            'label'     => Mage::helper('practicetest')->__('General Information'),
            'title'     => Mage::helper('practicetest')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('practicetest/adminhtml_container_tab_form')->toHtml(),
        ));
        
        $this->addTab('display_section',array(
            'label'		=> Mage::helper('practicetest')->__('Categories'),
            'content'   => $this->getLayout()->createBlock('practicetest/adminhtml_container_tab_extra')->toHtml(),
			// 'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
            // 'class'     => 'ajax',
	  ));
      return parent::_beforeToHtml();
    }
    
}