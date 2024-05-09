<?php

class Ccc_Banner_Block_Adminhtml_Banner_Edit_Tab_Image extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('banner');
        $this->setTitle(Mage::helper('banner')->__('Banner Information'));
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    protected function _prepareForm()
    {
        $model = Mage::registry("banner");
        // print_r($model);
        $form = new Varien_Data_Form();
        // $form->setHtmlIdPrefix('banner_image');
        // print_r($form);

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('banner')->__('General Information'), 'class' => 'fieldset-wide'));

        $fieldset->addField('image', 'image', array(
            'name' => 'image',
            'label'=> Mage::helper('banner')->__('Image'),
            'title'=> Mage::helper('banner')->__('Image'),
            'required' => false,
        ));
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    // protected function _getAdditionalElementTypes(){
    //     return array('image' => Mage::getConfig()->getBlockClassName('ccc_banner/adminhtml_banner_edit_form_element_image'));
    // }

}