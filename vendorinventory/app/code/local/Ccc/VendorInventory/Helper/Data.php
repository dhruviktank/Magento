<?php

class Ccc_VendorInventory_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getBrandNames()
    {
        // Get the resource model
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        
        // Define table names
        $optionValueTable = $resource->getTableName('eav_attribute_option_value');
        $optionTable = $resource->getTableName('eav_attribute_option');
        
        // Prepare the SQL query
        $select = $readConnection->select()
            ->from(array('a' => $optionTable), array('option_id'))
            ->join(
                array('t' => $optionValueTable),
                'a.option_id = t.option_id',
                array('value')
            )
            ->where('a.attribute_id = ?', 216);
        
        // Execute the query and fetch results
        $brandNames = $readConnection->fetchPairs($select);
        
        return $brandNames;
    }
    public function addBrand($brandName){
        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_write');
        
        $optionValueTable = $resource->getTableName('eav_attribute_option_value');
        $optionTable = $resource->getTableName('eav_attribute_option');
    }
}