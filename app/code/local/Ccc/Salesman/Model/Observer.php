<?php
class Ccc_Salesman_Model_Observer
{
    public function saveNewData()
    {
        $date = Mage::getModel('core/date')->date('y-m-d');
        echo $date;
        $startDate = $date . " 00:00:00";
        $endDate = $date . " 23:59:59";
        echo $startDate;

        $collection = Mage::getModel('salesman/commission')->getCollection();
        $collection->addFieldToFilter('created_at', ['from' => $startDate, 'to' => $endDate, 'datetime' => true]);
        Mage::log($collection->getSelect()->__toString(),null,'abc.log');

        $collection->getSelect()->join(
            array('salesman' => Mage::getSingleton('core/resource')->getTableName('admin/user')),
            'main_table.user_id = salesman.user_id',
            array('username' => 'salesman.username')
        );
        $collection->getSelect()->columns(
            array(
                'total_commission' => new Zend_Db_Expr('SUM(commission)'),
            )
        );
        $collection->getSelect()->group('order_id');
        $data = $collection->getData();
        $column = array_column($data, 'total_commission');
        array_multisort($column, SORT_DESC, $data);
        $overallRank = 1;
        foreach ($data as $key => $value) {
            $data[$key]['rank'] = $overallRank++;
        }
        $rank = 1;
        // Mage::log(Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($collection->getSelect()->__toString()),null,'abc.log');
        // Mage::log($collection->getSelect()->__toString(),null,'abc.log');
        // print_r($collection->getData());
        Mage::log($data, null, 'xyz.log');
    }

    public function assignPercentage(Varien_Event_Observer $Observer)
    {
        echo 121212;
        $adminUser = $Observer->getEvent()->getObject()->getData();
        $collection = Mage::getModel('admin/role')->getCollection()->addFieldToFilter('role_id', ['eq' => $adminUser['roles'][0]])->getFirstItem();
        $productPercentage = Mage::getStoreConfig('salesmann/percentage/product');
        $shippingPercentage = Mage::getStoreConfig('salesmann/percentage/shipping');
        $taxPercentage = Mage::getStoreConfig('salesmann/percentage/tax');
        $percentages = array(
            $productPercentage,
            $shippingPercentage,
            $taxPercentage
        );
        if ($collection->getData()['role_name'] == 'salesman') {
            $i = 0;
            foreach (Mage::getModel('salesman/metric')->getMetrics() as $metricValue) {
                $data['user_id'] = $adminUser['user_id'];
                $data['metric'] = $metricValue;
                $data['percentage'] = $percentages[$i++];
                Mage::getModel('salesman/metric')->setData($data)->save();
            }
        }
    }
}