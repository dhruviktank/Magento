<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry("configuration");
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('outlook')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getConfigurationId()) {
            $fieldset->addField('configuration_id', 'hidden', array(
                'name' => 'configuration_id',
            )
            );
        }

        $fieldset->addField('client_id', 'text', array(
            'name' => 'client_id',
            'label' => Mage::helper('outlook')->__('Client Id'),
            'title' => Mage::helper('outlook')->__('Client Id'),
            'required' => true,
        )
        );
        $fieldset->addField('client_secret', 'text', array(
            'name' => 'client_secret',
            'label' => Mage::helper('outlook')->__('Client Secret'),
            'title' => Mage::helper('outlook')->__('Client Secret'),
            'required' => true,
        )
        );
        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('outlook')->__('Is Active'),
            'title' => Mage::helper('outlook')->__('Is Active'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '2' => Mage::helper('outlook')->__('Yes'),
                '1' => Mage::helper('outlook')->__('No'),
            ),
        )
        );

        $fieldset->addField('api_url', 'text', array(
            'name' => 'api_url',
            'label' => Mage::helper('outlook')->__('API URL'),
            'title' => Mage::helper('outlook')->__('API URL'),
        )
        );
        $fieldset->addField('access_token', 'text', array(
            'name' => 'access_token',
            'label' => Mage::helper('outlook')->__('Access Token'),
            'title' => Mage::helper('outlook')->__('Access Token'),
            'disabled' => true,
        )
        );
        $fieldset->addField('refresh_token', 'text', array(
            'name' => 'refresh_token',
            'label' => Mage::helper('outlook')->__('Refresh Token'),
            'title' => Mage::helper('outlook')->__('Refresh Token'),
            'disabled' => true,
        )
        );
        if ($model->getConfigurationId()) {
            $fieldset->addField('login_button', 'note', array(
                'text' => $this->getButtonHtml(
                    Mage::helper('outlook')->__('Login'),
                    "window.open('{$this->getUrl('*/*/login', ['id' => $model->getConfigurationId()])}','_blank')",
                    'login'
                ),
            )
            );
        }
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}