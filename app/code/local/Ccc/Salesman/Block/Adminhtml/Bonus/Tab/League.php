<?php
class Ccc_Salesman_Block_Adminhtml_Bonus_Tab_League extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('salesman/bonus/league.phtml');
    }

    public function getUsersInLeague()
    {
        $id = $this->getRequest()->getParam('id');
        $data = Mage::getModel('salesman/bonus')->load($id);
        return $data->getUsersInLeague();   
    }
    public function getRank()
    {
        $id = $this->getRequest()->getParam('id');
        $data = Mage::getModel('salesman/bonus')->load($id);
        return $data->getRank();   
    }
    
    public function CreateCollection()
    {
        $id = $this->getRequest()->getParam('id');
        $leagueUser = Mage::getModel('salesman/bonusLeagueUser')->getCollection();
        $leagueUser->getSelect()->join(
            array('salesman' => Mage::getSingleton('core/resource')->getTableName('admin/user')),
            'main_table.user_id = salesman.user_id',
            array('username' => 'salesman.username')
        );
        if($leagueUser->addFieldToFilter('configuration_id',$id)->getData()){
            $data =  $leagueUser->getData();
            $leagueNumbers = array_column($data, 'league_number');
            $ranks = array_column($data, 'rank');

            array_multisort($leagueNumbers, SORT_ASC, $ranks, SORT_ASC, $data);
            return $data;
        }
        $data = Mage::getModel('salesman/bonus')->load($id);
        $group = $data->getUsersInLeague();
        $bonusMetric = $data->getMetric();

        $collection = Mage::getModel('salesman/commission')->getCollection();

        $subquery = clone $collection->getSelect();
        $subquery->reset(); // Reset any previous select queries
        $subquery->from(array('main_table' => $collection->getMainTable()))
            ->join(
                array('salesman' => Mage::getSingleton('core/resource')->getTableName('admin/user')),
                'main_table.user_id = salesman.user_id',
                array('username' => 'salesman.username')
            )
            ->group('user_id');
        $data = [];
        
        switch ($bonusMetric) {
            case 'Total Worked Orders':
                $subquery->columns(array(
                    'total upsold' => new Zend_Db_Expr('SUM(upsold)')
                ));
                $subquery->having(new Zend_Db_Expr('SUM(upsold) > 0'));
                $subquery->reset(Zend_Db_Select::GROUP);
                $subquery->group('order_id');
                $mainSelect = $collection->getSelect();
                $mainSelect->reset();
                $mainSelect->from(array('sub' => new Zend_Db_Expr('(' . $subquery . ')')))
                    ->columns(array(
                        'count(*) as total_worked_orders'
                    ))
                    ->group('sub.user_id')
                    ->order('total_worked_orders DESC');

                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($mainSelect);
                break;

            case 'Total Upsell Orders':
                $subquery->columns(array(
                    'total upsold' => new Zend_Db_Expr('SUM(upsold)')
                ));
                $subquery->reset(Zend_Db_Select::GROUP);
                $subquery->group('order_id');
                $mainSelect = $collection->getSelect();
                $mainSelect->reset();
                $mainSelect->from(array('sub' => new Zend_Db_Expr('(' . $subquery . ')')))
                    ->columns(array(
                        'count(*) as total_upsell_orders'
                    ))
                    ->group('sub.user_id')
                    ->order('total_upsell_orders DESC');

                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($mainSelect);
                break;

            case "Total Commission Orders":
                $subquery->columns(array(
                    'total commission' => new Zend_Db_Expr('SUM(commission)')
                ));
                $subquery->reset(Zend_Db_Select::GROUP);
                $subquery->group('order_id');
                $subquery->having(new Zend_Db_Expr('SUM(commission) != 0 '));
                $mainSelect = $collection->getSelect();
                $mainSelect->reset();
                $mainSelect->from(array('sub' => new Zend_Db_Expr('(' . $subquery . ')')))
                    ->columns(array(
                        'count(*) as total_commission_orders'
                    ))
                    ->group('sub.user_id')
                    ->order('total_commission_orders DESC');

                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($mainSelect);
                break;

            case "Total Upsold":
                $subquery->columns(array(
                    'total upsold' => new Zend_Db_Expr('SUM(upsold)')
                ))
                    ->order('total upsold DESC');

                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Total Commission":
                $subquery->columns(array(
                    'total commission' => new Zend_Db_Expr('SUM(commission)')
                ))
                    ->order('total commission DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Product Upsold":
                $subquery->columns(array(
                    'product upsold' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'product\' THEN upsold ELSE 0 END)'),
                ))
                    ->order('product upsold DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Shipping Upsold":
                $subquery->columns(array(
                    'shipping upsold' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'shipping\' THEN upsold ELSE 0 END)'),
                ))
                    ->order('shipping upsold DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Tax Upsold":
                $subquery->columns(array(
                    'tax upsold' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'tax\' THEN upsold ELSE 0 END)'),
                ))
                    ->order('tax upsold DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Product Commission":
                $subquery->columns(array(
                    'product commission' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'product\' THEN commission ELSE 0 END)'),
                ))
                    ->order('product commission DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Shipping Commission":
                $subquery->columns(array(
                    'shipping commission' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'shipping\' THEN commission ELSE 0 END)'),
                ))
                    ->order('shipping commission DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            case "Tax Commission":
                $subquery->columns(array(
                    'tax commission' => new Zend_Db_Expr('SUM(CASE WHEN main_table.metric = \'tax\' THEN commission ELSE 0 END)'),
                ))
                    ->order('tax commission DESC');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;

            default:
                $subquery->where('1 = 0');
                $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($subquery);
                break;
        }
        return $data;
    }
}