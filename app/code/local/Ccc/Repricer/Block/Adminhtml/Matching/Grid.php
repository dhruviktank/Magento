<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('matchingBlockGrid');
        $this->setDefaultSort('block_identifier');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);

        // $this->setTemplate('repricer/matching_grid.phtml');
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }


    protected function _prepareCollection()
    {

        $collection = Mage::getModel('repricer/matching')->getCollection();
        $select = $collection->getSelect();
        $columns = [
            'updated_date' => 'main_table.updated_date',
            // 'competitor_updated_date' => 'rc.updated_date',
            'product_id' => 'product_id',
            'repricer_id' => 'repricer_id',
            'reason' => 'reason',
            'competitor_url' => 'competitor_url',
            'competitor_sku' => 'competitor_sku',
            'competitor_price' => 'competitor_price',
            'competitor_name' => 'rc.name',
            'product_name' => 'ev.value',
            'product_sku' => 'CP.sku'

        ];

        $select->join(
            array('rc' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
            'rc.competitor_id = main_table.competitor_id',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $select->join(
            array('ev' => 'catalog_product_entity_varchar'),
            'ev.entity_id = product_id AND ev.attribute_id = 71 AND ev.store_id = 0',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $select->join(
            array('CP' => 'catalog_product_entity'),
            'CP.entity_id = product_id',
            ['']
        )
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $select->group('product_id');
        $select->order('main_table.product_id', 'ASC')
            ->order('repricer_id', 'ASC');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {


        // $this->addColumn(
        //     'repricer_id',
        //     array(
        //         'header' => Mage::helper('repricer')->__('Repricer Id'),
        //         'width' => '50px',
        //         'type' => 'number',
        //         'index' => 'repricer_id',
        //     )
        // );
        // $this->addColumn(
        //     'product_id',
        //     array(
        //         'header' => Mage::helper('repricer')->__('Product Id'),
        //         'width' => '50px',
        //         'type' => 'number',
        //         'index' => 'product_id',

        //     )
        // );
        // $displayedProductIds = [];
        $this->addColumn(
            'product_name',
            array(
                'header' => Mage::helper('repricer')->__('Product Info'),
                'align' => 'left',
                'index' => 'product_name',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_productinfo',
                'filter_condition_callback' => array($this, '_filterProductNameCondition')
            )
        );
        $this->addColumn(
            'competitor_name',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Name'),
                'width' => '50px',
                'type' => 'options',
                'options' => Mage::getModel('repricer/matching')->getCompArray(),
                'index' => 'competitor_name',
                'filter_condition_callback' => array($this, '_filterCompetitorNameCondition'),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        $this->addColumn(
            'competitor_url',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Url'),
                'align' => 'left',
                'index' => 'competitor_url',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        $this->addColumn(
            'competitor_sku',
            array(
                'header' => Mage::helper('repricer')->__('Competitor Sku'),
                'align' => 'left',
                'index' => 'competitor_sku',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        $this->addColumn(
            'competitor_price',
            array(
                'type' => 'number',
                'header' => Mage::helper('repricer')->__('Competitor Price'),
                'align' => 'left',
                'index' => 'competitor_price',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        $this->addColumn(
            'reason',
            array(
                'header' => Mage::helper('repricer')->__('Reason'),
                'index' => 'reason',
                'type' => 'options',
                // 'options' => Mage::getModel('repricer/matching')->getReasonOptionArray(),
                'options' => Mage::helper('repricer')->getReasonOptionArray(),
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('repricer')->__('Updated Date'),
                'align' => 'left',
                'index' => 'updated_date',
                'type' => 'datetime',
                'filter_condition_callback' => array($this, '_filterUpdateDateCondition'),
                // 'renderer' => 'repricer/adminhtml_matching_grid_renderer_datetime',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
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
                        'onclick' => 'editRow(this)',
                        'field' => 'repricer_id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'edit',
                'renderer' => 'repricer/adminhtml_matching_grid_renderer_column'
            )
        );
        // $this->getColumns();

        return parent::_prepareColumns();
    }

    public function addColumn($columnId, $column)
    {
        if (isset($column['is_allowed']) && $column['is_allowed'] === false) {
            return;
        }
        return parent::addColumn($columnId, $column);
    }
    // protected function _afterLoadCollection()
    // {
    //     $this->getCollection()->walk('afterLoad');
    //     parent::_afterLoadCollection();
    // }
    // protected function _filterStoreCondition($collection, $column)
    // {
    //     if (!$value = $column->getFilter()->getValue()) {
    //         return;
    //     }
    //     $fieldName = $column->getFilterIndex() ?: $column->getIndex();
    //     switch ($fieldName) {
    //         case 'product_name':
    //             $this->_filterProductNameCondition($collection, $column);
    //             break;
    //         case 'competitor_name':
    //             $this->_filterCompetitorNameCondition($collection, $column);
    //             break;
    //     }
    // }

    protected function _filterProductNameCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $collection->addFieldToFilter(
            array('ev.value', 'CP.sku'), // Use an array for OR condition
            array(
                array('eq' => $value),
                array('eq' => $value)
            )
        );
    }

    protected function _filterCompetitorNameCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('main_table.competitor_id', $value);
    }
    // public function getRowUrl($row)
    // {
    //     return $this->getUrl('*/*/edit', array('repricer_id' => $row->getId()));
    // }

    protected function _filterUpdateDateCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $dateFilter = [];
        if (isset($value['from'])) {
            $dateFilter['gteq'] = date('Y-m-d H:i:s', strtotime($value['from']));
        }

        if (isset($value['to'])) {
            $dateFilter['lteq'] = date('Y-m-d 23:59:59', strtotime($value['to']));
        }

        if (!empty($dateFilter)) {
            $collection->addFieldToFilter('main_table.updated_date', $dateFilter);
        }
    }

}
