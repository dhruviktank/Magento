<?php

class Ccc_FilterReport_Block_Adminhtml_Report_Table extends Mage_Core_Block_Template{
    public function __construct()
	{
        $this->setTemplate('filterreport/report/table.phtml');        
	}

    public function getReports(){
        $userId = $this->getRequest()->getParam('user_id');
        $collection = Mage::getModel('filterreport/report')->getCollection();
        $collection->addFieldtoFilter('user_id', $userId);
        $reports = $collection->getItems();
        return $reports;
    }
}