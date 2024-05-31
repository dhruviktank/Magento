<?php

class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract {
    public function getGridIdsJson()
    {
        if (!$this->getUseSelectAll()) {
            return '';
        }

        // $collection = Mage::getModel('repricer/matching')->getCollection()->getColumnValues('product_id');
        // $collection = $this->getParentBlock()->getCollection();

        // Mage::log(get_class($collection),null,'collection.log');
        // $gridIds = $this->getParentBlock()->getCollection()->getColumnValues('pc_comb');
        $gridId = $this->getParentBlock()->getCollection()->getSelect()->reset(Zend_Db_Select::LIMIT_COUNT);
        $gridId = $this->getParentBlock()->getCollection()->getSelect()->reset(Zend_Db_Select::LIMIT_OFFSET);
        $gridId = $this->getParentBlock()->getCollection()->getSelect()->reset(Zend_Db_Select::COLUMNS)->columns([ 'pc_comb' => 'GROUP_CONCAT(CONCAT(main_table.product_id, "-", main_table.competitor_id) SEPARATOR ",")']);
        // $gridId2 = $this->getParentBlock()->getCollection()->getAllIds();

        $gridIds = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchCol($gridId);
        // Mage::log($gridIds,null,'yash.log');
        Mage::log($gridIds,null,'yash2.log');
        // Mage::log($gridId2,null,'yash3.log');

        if(!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}