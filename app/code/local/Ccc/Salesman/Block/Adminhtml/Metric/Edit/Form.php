<?php
class Ccc_Salesman_Block_Adminhtml_Metric_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman');
        $this->setTitle(Mage::helper('banner')->__('Block Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('salesman');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('*/*/save'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );

        $form->setHtmlIdPrefix('salesman_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('salesman')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getEntityId()) {
            $fieldset->addField(
                'entity_id',
                'hidden',
                array(
                    'name' => 'entity_id',
                )
            );
        }

        $collection = Mage::getResourceModel('admin/user_collection');
        $option = array();
        foreach ($collection as $item) {
            $option[$item->getId()] = $item->getUsername();
        }
        $fieldset->addField(
            'user_id',
            'select',
            array(
                'name' => 'user_id',
                'label' => Mage::helper('salesman')->__('User'),
                'title' => Mage::helper('salesman')->__('User'),
                'required' => true,
                'options' => $option,
            )
        );
        
        $fieldset->addField(
            'metric',
            'select',
            array(
                'label' => Mage::helper('salesman')->__('Metric'),
                'title' => Mage::helper('salesman')->__('Metric'),
                'name' => 'metric',
                'required' => true,
                // 'options' => $metrics
                'options' => array(
                    Ccc_Salesman_Model_Metric::PRODUCT_METRIC => Mage::helper('salesman')->__('Product'),
                    Ccc_Salesman_Model_Metric::SHIPPING_METRIC => Mage::helper('salesman')->__('Shipping'),
                    Ccc_Salesman_Model_Metric::TAX_METRIC => Mage::helper('salesman')->__('Tax'),
                )
            )
        );

        if (!$model->getId()) {
            $model->setData('metric', 'Product');
        }

        $fieldset->addField(
            'percentage',
            'text',
            array(
                'name' => 'percentage',
                'label' => Mage::helper('salesman')->__('Percentage'),
                'title' => Mage::helper('salesman')->__('Percentage'),
                'required' => true,
            )
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}