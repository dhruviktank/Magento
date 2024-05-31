<?php
class Ccc_Salesman_Model_Scheduler
{
    public function saveBefore()
    {
        $todayDate = date('Y-m-d');
        echo 123;
        $configCollection = Mage::getModel('salesman/bonus')->getCollection()->addFieldToFilter('start_date', ['lteq' => $todayDate])
            ->addFieldToFilter('end_date', ['gteq' => $todayDate])
            ->addFieldToSelect('metric')
            ->addFieldToSelect('start_date')
            ->addFieldToSelect('end_date')
            ->addFieldToSelect('entity_id');

        foreach ($configCollection as $item) {
            $mainCollection = Mage::getModel('salesman/bonusLeagueUser')->getCollection()->addFieldToFilter('configuration_id', ['eq' => $item->getEntityId()])
                ->addFieldToSelect('user_id')
                ->addFieldToSelect('league_number')
                ->addFieldToSelect('bonus', 'amount')
                ->addFieldToSelect('configuration_id')->getData();
            $i = 0;

            foreach ($mainCollection as $key => $data) {
                $mainCollection[$key]['metric'] = $item->getMetric();
                $mainCollection[$key]['start_date'] = $item->getStartDate();
                $mainCollection[$key]['end_date'] = $item->getEndDate();
                $mainCollection[$key]['description'] = "Bonus = " . $data['amount'] . "$";
                $mainCollection[$key]['rank'] = ++$i;
            }

            $bonus = array_column($mainCollection, 'amount');
            array_multisort($bonus, SORT_DESC, $mainCollection);
            
            try {
                $resource = Mage::getSingleton('core/resource');
                $connection = $resource->getConnection('core_write');
                $getBonusLeagueTable = $resource->getTableName('salesman/salesman_bonus_league');

                $connection->beginTransaction();
                $connection->delete($getBonusLeagueTable, ['metric =?' => $item->getMetric()]);

                foreach ($mainCollection as $key => $data) {
                    Mage::log($data, null, 'bonus.log');
                    unset($data['configuration_id']);
                    if ($data['metric'] == $item->getMetric()) {
                        $connection->insert($getBonusLeagueTable, $data);
                        $connection->commit();
                    }
                }
            } catch (Exception $e) {
                $connection->rollBack();
                echo $e->getMessage();
            }
        }
    }

    public function saveAfterEndDate()
    {
        $datetime = date('Y-m-d');

        $collectionData = Mage::getModel('bonus/bonusLeague')->getCollection()->addFieldToFilter('end_date', ['eq' => $datetime])->getData();
        foreach ($collectionData as $data) {
            Mage::getModel('bonus/bonus')->setData($data); //->save();
        }
    }
}