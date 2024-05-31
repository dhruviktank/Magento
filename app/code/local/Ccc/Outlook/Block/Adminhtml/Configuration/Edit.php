<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'outlook';
        $this->_controller = 'adminhtml_configuration';
        $this->_objectId = 'configuration_id';
        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('outlook')->__('Save Block'));
        $this->_updateButton('delete', 'label', Mage::helper('outlook')->__('Delete Block'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('outlook')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

    }
    public function getHeaderText()
    {
        if (Mage::registry('configuration')->getId()) {
            return Mage::helper('outlook')->__("Edit Configuration '%s'", $this->escapeHtml(Mage::registry('configuration')->getName()));
        } else {
            return Mage::helper('outlook')->__('New Configuration');
        }
    }
}