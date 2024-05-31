<?php

class Ccc_VendorInventory_Model_Resource_Items_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init("vendorinventory/items");
    }
}