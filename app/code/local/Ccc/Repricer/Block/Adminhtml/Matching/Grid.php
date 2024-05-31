<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_massactionBlockName = 'repricer/adminhtml_matching_grid_massaction';
    public function __construct()
    {
        parent::__construct();
        $this->setId('matchingBlockGrid');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('repricer/matching')->getCollection();
        $select = $collection->getSelect();
        $columns = [
            'repricer_id' => 'repricer_id',
            'product_id' => 'product_id',
            'product_sku' => 'CPE.sku',
            'competitor_name' => 'CRC.name',
            'competitor_url' => 'competitor_url',
            'competitor_sku' => 'competitor_sku',
            'competitor_price' => 'competitor_price',
            'product_name' => 'CPEV.value',
            'updated_date' => 'main_table.updated_date',
            'reason' => 'reason',
            'pc_comb' => 'GROUP_CONCAT(CONCAT(main_table.product_id, "-", main_table.competitor_id) SEPARATOR ",")'
        ];

        $select->join(
            array('CRC' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
            'CRC.competitor_id = main_table.competitor_id',
            ['']
        );

        $select->join(
            array('CPEV' => Mage::getModel('core/resource')->getTableName('catalog_product_entity_varchar')),
            'CPEV.entity_id = product_id AND CPEV.attribute_id = 71 AND CPEV.store_id = 0',
            ['']
        );

        $select->join(
            array('CPE' => Mage::getModel('core/resource')->getTableName('catalog_product_entity')),
            'product_id = CPE.entity_id',
            ['']
        );
        $select->group('product_id')->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);
        // echo "<pre>";
        // print_r($collection->getData());
        // die;
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'product_name',
            array(
                'header' => Mage::helper('repricer')->__('Product Name'),
                // 'width' => '300px', // Adjust the width as needed
                'type' => 'text',
                'index' => 'product_name',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_product',
                'filter_condition_callback' => array($this, '_filterProductName')
            )
        );
        $this->addColumn(
            'pc_comb',
            array(
                'header' => Mage::helper('repricer')->__('Mass Action'),
                'width' => '5px', // Adjust the width as needed
                'index' => 'pc_comb',
                'align' => 'center',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor',
                'header_css_class' => 'heading-pc-col',
                'column_css_class' => 'pc-col',
                // 'style' => 'margin-left: 0; ',
                // 'column_css_class' => 'no-display', // Add this CSS class to hide the column
                // 'header_css_class' => 'no-display',
                // 'filter_condition_callback' => array($this, '_filterProductName')
            )
        );
        $this->addColumn(
            'competitor_name',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Name'),
                // 'width' => '150px',
                'type' => 'options',
                'index' => 'competitor_name',
                'options' => Mage::getModel('repricer/competitor')->getCompetitors(),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor',
                'filter_condition_callback' => array($this, '_filterCompetitorName')
            )
        );
        $this->addColumn(
            'competitor_url',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Url'),
                // 'width' => '400px',
                'align' => 'left',
                'index' => 'competitor_url',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor'
            )
        );
        $this->addColumn(
            'competitor_sku',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Sku'),
                'align' => 'left',
                // 'width' => '100px',
                'index' => 'competitor_sku',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor'
            )
        );
        $this->addColumn(
            'competitor_price',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Price'),
                'align' => 'left',
                // 'width' => '100px',
                'type' => 'number',
                'index' => 'competitor_price',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor'
            )
        );
        $this->addColumn(
            'reason',
            array(
                'header' => Mage::helper('repricer')->__('Reason'),
                'index' => 'reason',
                // 'width' => '150px',
                'type' => 'options',
                'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor'
            )
        );
        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('repricer')->__('updated_date'),
                'align' => 'left',
                'index' => 'updated_date',
                'type' => 'datetime',
                'filter_condition_callback' => array($this, '_filterUpdateDateCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor'
                // 'renderer' => 'repricer/adminhtml_matching_grid_renderer_datetime',
            )
        );
        $this->addColumn(
            'edit',
            array(
                'header' => Mage::helper('repricer')->__('Edit'),
                'align' => 'left',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('repricer')->__('Edit'),
                        'url' => array(
                            'base' => '*/*/edit',
                        ),
                        'field' => 'repricer_id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'edit',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_competitor',
            )
        );
        return parent::_prepareColumns();
    }

    protected function _filterProductName($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $collection->getSelect()->where("CPEV.value LIKE '%$value%' OR CPE.sku LIKE '%$value%'");

        return $this;
    }
    protected function _filterCompetitorName($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $collection->getSelect()->where("main_table.competitor_id LIKE ?", "%$value%");

        return $this;
    }
    protected function _filterUpdateDateCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        if (isset($value['from'])) {
            $value['from'] = date('Y-m-d 00:00:00', strtotime($value['from']));

            $collection->addFieldToFilter('main_table.updated_date', array('from' => $value['from'], 'datetime' => true));
        }
        if (isset($value['to'])) {
            $value['to'] = date('Y-m-d 23:59:59', strtotime($value['to']));
            $collection->addFieldToFilter('main_table.updated_date', array('to' => $value['to'], 'datetime' => true));
        }
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('pc_comb');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('pc_comb'); // Change to 'banner_id'
        $reasonArr = Mage::getModel('repricer/matching')->getReasonOptionArray();

        array_unshift($reasonArr, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem(
            'reason',
            array(
                'label' => Mage::helper('repricer')->__('Change Reason'),
                'url' => $this->getUrl('*/*/massReason', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'reason',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('repricer')->__('Reason'),
                        'values' => $reasonArr
                    )
                )
            )
        );
        return $this;
    }
}