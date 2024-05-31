<?php

class Ccc_Banner_Block_Adminhtml_Banner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    // protected function _prepareLayout()
    // {
    //     parent::_prepareLayout();
    // }

    protected function _prepareForm()
    {
        $model = Mage::registry("banner");
        $form = new Varien_Data_Form();

        // $form->setHtmlIdPrefix('banner_main');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('banner')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getBannerId()) {
            $fieldset->addField('banner_id', 'hidden', array(
                'name' => 'banner_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('banner')->__('Banner Title'),
            'title'     => Mage::helper('banner')->__('Banner Title'),
            'required'  => true,
        ));
        $fieldset->addField('content', 'text', array(
            'name'      => 'content',
            'label'     => Mage::helper('banner')->__('Content'),
            'title'     => Mage::helper('banner')->__('Content'),
            'required'  => true,
        ));
        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('banner')->__('Status'),
            'title'     => Mage::helper('banner')->__('Status'),
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('banner')->__('Enabled'),
                '0' => Mage::helper('banner')->__('Disabled'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $fieldset->addField('show_on', 'text', array(
            'name'      => 'show_on',
            'label'     => Mage::helper('banner')->__('show on'),
            'title'     => Mage::helper('banner')->__('show on'),
            'required'  => true,
        ));
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    // protected function _getAdditionalElementTypes(){
    //     return array('image' => Mage::getConfig()->getBlockClassName('ccc_banner/adminhtml_banner_edit_form_element_image'));
    // }

}