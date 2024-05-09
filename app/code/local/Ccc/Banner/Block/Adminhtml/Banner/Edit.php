<?php

class Ccc_Banner_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'banner';
        $this->_controller = 'adminhtml_banner';
        $this->_objectId = 'banner_id';
        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('banner')->__('Save Block'));
        $this->_updateButton('delete', 'label', Mage::helper('banner')->__('Delete Block'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('banner')->__('Save and Continue Edit'),
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
        if (Mage::registry('banner')->getId()) {
            return Mage::helper('banner')->__("Edit Banner '%s'", $this->escapeHtml(Mage::registry('banner')->getName()));
        } else {
            return Mage::helper('banner')->__('New Banner');
        }
    }
    protected function _getAdditionalElementTypes(){
        return array('image' => Mage::getConfig()->getBlockClassName('ccc_banner/adminhtml_banner_edit_form_element_image'));
    }
}