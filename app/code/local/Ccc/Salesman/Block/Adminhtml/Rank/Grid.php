<?php

class Ccc_Salesman_Block_Adminhtml_Rank_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_rank_grid');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        $this->setTemplate('salesman/rank.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesman/commission')->getCollection();
        $collection->getSelect()->join(
            array('salesman' => Mage::getSingleton('core/resource')->getTableName('admin/user')),
            'main_table.user_id = salesman.user_id',
            array('username' => 'salesman.username')
        );
        $columns = array(
            'product' => 'SUM(CASE WHEN metric = \'' . Ccc_Salesman_Model_Metric::PRODUCT_METRIC . '\' THEN upsold ELSE 0 END)',
            'shipping' => 'SUM(CASE WHEN metric = \'' . Ccc_Salesman_Model_Metric::SHIPPING_METRIC . '\' THEN upsold ELSE 0 END)',
            'tax' => 'SUM(CASE WHEN metric = \'' . Ccc_Salesman_Model_Metric::TAX_METRIC . '\' THEN upsold ELSE 0 END)',
            'total_upsold' => 'SUM(upsold)',
            'total_commission' => 'SUM(commission)'
        );
        
        foreach ($columns as $alias => $expression) {
            $collection->getSelect()->columns(array($alias => new Zend_Db_Expr($expression)));
        }
        $collection->getSelect()->group('main_table.user_id');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getSalesmanData()
    {
        $collection = $this->getCollection()->getData();
        
        $column = array_column($collection, 'total_commission');
        array_multisort($column, SORT_DESC, $collection);
        $overallRank = 1;
        foreach($collection as $key => $value){
            $collection[$key]['overall_rank'] = $overallRank++;
        }

        $productColumn = array_column($collection, 'product');
        array_multisort($productColumn, SORT_DESC, $collection);
        $productRank = 1;
        foreach ($collection as $key => $value) {
            $collection[$key]['product_rank'] = $productRank++;
        }
        
        $shippingColumn = array_column($collection, 'shipping');
        array_multisort($shippingColumn, SORT_DESC, $collection);
        $shippingRank = 1;
        foreach ($collection as $key => $value) {
            $collection[$key]['shipping_rank'] = $shippingRank++;
        }
        
        $taxColumn = array_column($collection, 'tax');
        array_multisort($taxColumn, SORT_DESC, $collection);
        $taxRank = 1;
        foreach ($collection as $key => $value) {
            $collection[$key]['tax_rank'] = $taxRank++;
        }
        return $collection;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}