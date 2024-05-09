<?php

class Ccc_PracticeTest_Block_Adminhtml_Container extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'practicetest';
        $this->_controller = 'adminhtml_practicetest';

        $this->_updateButton('save', 'label', Mage::helper('practicetest')->__('Save Test'));
        $this->_updateButton('delete', 'label', Mage::helper('practicetest')->__('Delete Test'));

        $this->addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
    }

    public function getHeaderText(){
        return Mage::helper('practicetest')->__('Test Form');
    }

}