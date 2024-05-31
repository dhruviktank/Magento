<?php

class Ccc_FilterReport_Block_Adminhtml_Filter_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('filterreport/grid.phtml');
        // $this->_blockGroup = "filterreport";
        // $this->_controller = "adminhtml_report";
    }

    public function getUsers(){
        $users = Mage::getModel('admin/user')->getCollection();
        return $users->getItems();
    }
}