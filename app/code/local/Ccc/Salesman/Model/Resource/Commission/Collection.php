<?php

class Ccc_Salesman_Model_Resource_Commission_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('salesman/commission');
    }

    // public function getSelectCountSql()
    // {
    //     $this->_renderFilters();

    //     $unionSelect = clone $this->getSelect();

    //     $unionSelect->reset(Zend_Db_Select::ORDER);
    //     $unionSelect->reset(Zend_Db_Select::LIMIT_COUNT);
    //     $unionSelect->reset(Zend_Db_Select::LIMIT_OFFSET);

    //     $countSelect = clone $this->getSelect();
    //     $countSelect->reset();
    //     $countSelect->from(array('a' => $unionSelect), 'COUNT(*)');

    //     return $countSelect;
    // }
}