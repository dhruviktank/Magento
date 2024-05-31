<?php

class Ccc_Salesman_Model_Resource_BonusLeagueUser extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('salesman/salesman_bonus_league_user', 'entity_id');
    }
}