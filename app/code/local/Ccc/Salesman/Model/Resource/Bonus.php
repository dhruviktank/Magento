<?php

class Ccc_Salesman_Model_Resource_Bonus extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('salesman/salesman_bonus_configuration', 'entity_id');
    }
}