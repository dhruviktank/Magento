<?php

class Ccc_FilterReport_Block_Adminhtml_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product{
    protected function _prepareLayout()
    {
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Product'),
            'onclick' => "setLocation('{$this->getUrl('*/*/new')}')",
            'class'   => 'add'
        ));

        $this->_addButton('save_report', array(
            'label' => Mage::helper('catalog')->__('Save Report'),
            'class' => 'save-report'
        ));

        $this->setChild('grid', $this->getLayout()->createBlock('adminhtml/catalog_product_grid', 'product.grid'));
        // return parent::_prepareLayout();
    }
}