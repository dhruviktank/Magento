<?php

class Ccc_FilterReport_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{

    protected function _prepareLayout()
    {
        $this->setChild(
            'save_report_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label' => Mage::helper('adminhtml')->__('Save Report'),
                        'onclick' => $this->getJsObjectName() . '.saveFilter()',
                    )
                )
        );
        return parent::_prepareLayout();
    }
    public function getSaveReportButtonHtml()
    {
        return $this->getChildHtml('save_report_button');
    }
    public function getMainButtonsHtml()
    {
        $html = '';
        if ($this->getFilterVisibility()) {
            if (Mage::getStoreConfig('filterreport/general/allow_save_report') == 1)
                $html .= $this->getSaveReportButtonHtml();
            $html .= $this->getResetFilterButtonHtml();
            $html .= $this->getSearchButtonHtml();
        }
        return $html;
    }
    protected function _prepareCollection()
    {
        // $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        // $filter = $this->getParam('filter', null);
        // $filterReportCollection = Mage::getModel('filterreport/report')
        //     ->getCollection()
        //     ->addFieldToFilter('user_id', $userId)
        //     ->addFieldToFilter('report_type', 1);
        // // if (is_null($filter)) {
        //     $data = $this->helper('adminhtml')
        //         ->prepareFilterString($filterReportCollection->getFirstItem()->getFilterData());
        //     $this->_setFilterValues($data);
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField(
                'qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            );
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'brand',
                'catalog_product/brand',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
        } else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('brand', 'catalog_product/brand', 'entity_id', null, 'left');
            $collection->joinAttribute('sold_count', 'catalog_product/sold_count', 'entity_id', null, 'left');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }
        $this->setCollection($collection);

        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }
    protected function _prepareColumns()
    {
        $this->addColumnAfter(
            'sold_count',
            array(
                'header' => Mage::helper('catalog')->__('Sold Count'),
                'width' => '70px',
                'index' => 'sold_count',
                'type' => 'number',
            ),
            'qty'
        );
        $this->addColumnAfter(
            'brand',
            array(
                'header' => Mage::helper('catalog')->__('Brand'),
                'width' => '70px',
                'index' => 'brand',
                'type' => 'options',
                'options' => Mage::getModel('catalog/product_brand')->getOptionArray(),
            ),
            'sold_count'
        );
        parent::_prepareColumns();

    }
}