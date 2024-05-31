<?php

class Ccc_Salesman_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_totals = [];
    protected $_users = [];
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_commission_grid');
        $this->setTemplate("salesman/summary.phtml");
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesman/commission')->getCollection();

        $collection->getSelect()->join(
            array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
            'main_table.order_id = order.entity_id',
            array('customer_name' => ('CONCAT(order.customer_firstname, " ", order.customer_lastname)'))
        );

        $collection->getSelect()->join(
            array('salesman' => Mage::getSingleton('core/resource')->getTableName('admin/user')),
            'main_table.user_id = salesman.user_id',
            array('username' => 'salesman.username')
        );

        
        $columns = array(
            'order_id' => 'order.increment_id',
            'product_upsold' => 'SUM(CASE WHEN metric = \''. Ccc_Salesman_Model_Metric::PRODUCT_METRIC .'\' THEN upsold ELSE 0 END)',
            'shipping_upsold' => 'SUM(CASE WHEN metric = \'' . Ccc_Salesman_Model_Metric::SHIPPING_METRIC . '\' THEN upsold ELSE 0 END)',
            'tax_upsold' => 'SUM(CASE WHEN metric = \'' . Ccc_Salesman_Model_Metric::TAX_METRIC . '\' THEN upsold ELSE 0 END)',
            'total_upsold' => 'SUM(upsold)',
            'total_commission' => 'SUM(commission)',
        );
        $collection->getSelect()->group('main_table.order_id');
        
        foreach ($columns as $alias => $expression) {
            $collection->getSelect()->columns(array($alias => new Zend_Db_Expr($expression)));
        }

        $collection->addFilterToMap('created_at', 'main_table.created_at');
        // echo $collection->getSelect()->__toString();

        $filter = $this->getParam($this->getVarNameFilter(), null);

        $data = [];
        if (is_string($filter)) {
            $data = $this->helper('adminhtml')->prepareFilterString($filter);
        }

        $dateFilterApplied = false;
        $salesmen = [];
        foreach ($data as $key => $value) {
            if ($key === 'created_at' && ($value['from'] !== 'undefined/undefined/' || $value['to'] !== 'undefined/undefined/')) {
                $dateFilterApplied = true;
            } elseif (strpos($key, 'username_') === 0) {
                $this->_users[] = $value;
                $salesman = substr($key, strlen('username_'));
                $salesmen[] = $salesman;
            }
        }

        if ($dateFilterApplied && !empty($salesmen)) {
            $collection->addFieldToFilter('username', ['in' => $salesmen]);
        } else {
            $collection->addFieldToFilter('main_table.entity_id', ['eq' => 0]);
        }
        // echo $collection->getSelect()->__toString();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('salesman')->__('Date'),
                'index' => 'created_at',
                'type' => 'datetime',
                'filter_date' => true,
            )
        );

        $this->addColumn(
            'order_id',
            array(
                'header' => Mage::helper('salesman')->__('Order Number'),
                'index' => 'order_id',
                'filter_index' => 'order.increment_id',
            )
        );

        $this->addColumn(
            'username',
            array(
                'header' => Mage::helper('salesman')->__('Salesman Name'),
                'index' => 'username',
                'filter_index' => 'username',
            )
        );

        $this->addColumn(
            'customer_name',
            array(
                'header' => Mage::helper('salesman')->__('Customer Name'),
                'index' => 'customer_name',
                'filter_index' => 'order.customer_firstname',
            )
        );

        $this->addColumn(
            'product_upsold',
            array(
                'header' => Mage::helper('salesman')->__('Product Upsold'),
                'index' => 'product_upsold',
                'type' => 'currency',
                'filter_condition_callback' => array($this, '_filterUpsold')
            )
        );

        $this->addColumn(
            'shipping_upsold',
            array(
                'header' => Mage::helper('salesman')->__('Shipping Upsold'),
                'index' => 'shipping_upsold',
                'type' => 'currency',
                'filter_condition_callback' => array($this, '_filterUpsold')
            )
        );

        $this->addColumn(
            'tax_upsold',
            array(
                'header' => Mage::helper('salesman')->__('Tax Upsold'),
                'index' => 'tax_upsold',
                'type' => 'currency',
                'filter_condition_callback' => array($this, '_filterUpsold')
            )
        );

        $this->addColumn(
            'total_upsold',
            array(
                'header' => Mage::helper('salesman')->__('Total Upsold'),
                'index' => 'total_upsold',
                'type' => 'currency',
                'filter_condition_callback' => array($this, '_filterUpsold'),
            )
        );

        $this->addColumn(
            'total_commission',
            array(
                'header' => Mage::helper('salesman')->__('Total Commission'),
                'index' => 'total_commission',
                'type' => 'currency',
                'filter_condition_callback' => array($this, '_filterUpsold'),
            )
        );

        $this->addColumn(
            'is_paid',
            array(
                'header' => Mage::helper('salesman')->__('Is Paid'),
                'index' => 'is_paid',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('salesman')->__('No'),
                    1 => Mage::helper('salesman')->__('Yes'),
                ),
            )
        );
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $totals = array(
            'product_upsold' => 0,
            'shipping_upsold' => 0,
            'tax_upsold' => 0,
            'total_upsold' => 0,
            'avg_upsold' => 0,
            'total_commission' => 0,
            'avg_commission' => 0
        );

        $number = sizeof($this->getCollection());
        if ($number > 0) {
            foreach ($this->getCollection() as $item) {
                $totals['product_upsold'] += $item->getProductUpsold();
                $totals['shipping_upsold'] += $item->getShippingUpsold();
                $totals['tax_upsold'] += $item->getTaxUpsold();
                $totals['total_upsold'] += $item->getTotalUpsold();
                $totals['total_commission'] += $item->getTotalCommission();
            }
            $totals['avg_upsold'] = $totals['total_upsold'] / $number;
            $totals['avg_commission'] = $totals['total_commission'] / $number;
        } else {
            $totals['avg_upsold'] = 0;
            $totals['avg_commission'] = 0;
        }
        $this->_totals = $totals;

        parent::_afterLoadCollection();
        $this->getSummaryReportData();
    }

    protected function _filterUpsold($collection, $column)
    {
        $value = $column->getFilter()->getValue();

        if (!is_array($value) || count($value) != 2) {
            return $this;
        }

        $field = $column->getIndex();
        $fieldNameParts = explode('_', $field);
        $fieldName = reset($fieldNameParts);

        $collection->getSelect()->having(
            "SUM(CASE WHEN main_table.metric = '{$fieldName}' THEN main_table.upsold ELSE 0 END) BETWEEN '{$value['from']}' AND '{$value['to']}'"
        );

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getSummaryReportData()
    {
        $collection = $this->getCollection();
        $totalOrders = sizeof($collection);
        $upsoldOrders = 0;

        foreach ($collection as $item) {
            if ($item->getTotalUpsold() > 0) {
                $upsoldOrders++;
            }
        }
        $upsoldPercentage = ($totalOrders > 0) ? round(($upsoldOrders / $totalOrders) * 100, 2) : 0;

        $summaryData = array(
            'today_rank' => '-',
            'upsold_orders' => "$upsoldOrders/$totalOrders - $upsoldPercentage%",
            'product_upsold' => $this->_totals['product_upsold'],
            'shipping_upsold' => $this->_totals['shipping_upsold'],
            'tax_upsold' => $this->_totals['tax_upsold'],
            'total_upsold' => $this->_totals['total_upsold'],
            'avg_upsold' => round($this->_totals['avg_upsold'], 2),
            'total_commission' => $this->_totals['total_commission'],
            'avg_commission' => round($this->_totals['avg_commission'], 2)
        );
        return $summaryData;
    }

    public function getSalesmanData()
    {
        $salesmanData = array();

        foreach ($this->_users as $user) {
            $upsoldOrders = 0;
            $totalOrders = 0;
            
            $totals = array(
                'product_upsold' => 0,
                'shipping_upsold' => 0,
                'tax_upsold' => 0,
                'total_upsold' => 0,
                'avg_upsold' => 0,
                'total_commission' => 0,
                'avg_commission' => 0
            );

            foreach ($this->getCollection() as $item) {
                if ($item->getUsername() == $user) {
                    $totalOrders++;
                    if ($item->getTotalUpsold() > 0) {
                        $upsoldOrders++;
                    }
                    $totals['product_upsold'] += $item->getProductUpsold();
                    $totals['shipping_upsold'] += $item->getShippingUpsold();
                    $totals['tax_upsold'] += $item->getTaxUpsold();
                    $totals['total_upsold'] += $item->getTotalUpsold();
                    $totals['total_commission'] += $item->getTotalCommission();
                }
            }
            if ($totalOrders > 0) {
                $totals['avg_upsold'] = $totals['total_upsold'] / $totalOrders;
                $totals['avg_commission'] = $totals['total_commission'] / $totalOrders;
            }
            $upsoldPercentage = ($totalOrders > 0) ? round(($upsoldOrders / $totalOrders) * 100) : 0;

            $salesmanData[$user] = array(
                'salesman_name' => $user,
                'upsold_orders' => "$upsoldOrders/$totalOrders - $upsoldPercentage%",
                'product_upsold' => $totals['product_upsold'],
                'shipping_upsold' => $totals['shipping_upsold'],
                'tax_upsold' => $totals['tax_upsold'],
                'total_upsold' => $totals['total_upsold'],
                'avg_upsold' => round($totals['avg_upsold'], 2),
                'total_commission' => $totals['total_commission'],
                'avg_commission' => round($totals['avg_commission'], 2)
            );

        }
            usort($salesmanData, function ($a, $b) {
                return $b['total_commission'] - $a['total_commission'];
            });

            $rank = 1;
            foreach ($salesmanData as &$salesman) {
                $salesman['rank'] = $rank;
                $rank++;
            }
        return $salesmanData;
    }

}