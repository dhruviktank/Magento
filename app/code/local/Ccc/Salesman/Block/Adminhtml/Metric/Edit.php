<?php
class Ccc_Salesman_Block_Adminhtml_Metric_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'entity_id';
        $this->_controller = 'adminhtml_metric';
        $this->_blockGroup = 'salesman';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('salesman')->__('Save Metric'));
        $this->_updateButton('delete', 'label', Mage::helper('salesman')->__('Delete Metric'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('metric_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('salesman')->getId()) {
            return Mage::helper('salesman')->__('Edit Metric');
        } else {
            return Mage::helper('salesman')->__('New Metric');
        }
    }
}