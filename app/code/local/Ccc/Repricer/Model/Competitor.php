<?php

class Ccc_Repricer_Model_Competitor extends Mage_Core_Model_Abstract{
    protected function _construct(){
        $this->_init('repricer/competitor');
    }
    public function getCompetitors()
    {
        $allCompetitors = $this->getCollection();
        $result = [];
        foreach ($allCompetitors as $competitor) {
            $result[$competitor->getCompetitorId()] = $competitor->getName();
        }
        return $result;
    }

}