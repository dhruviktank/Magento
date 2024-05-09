<?php

class Ccc_PracticeTest_Block_Adminhtml_Container_Tab_Extra extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('', array('legend' => Mage::helper('practicetest')->__('Extra Fields')));
        return parent::_prepareForm();
    }
}