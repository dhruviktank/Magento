<?php

class Ccc_FilterReport_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "filterreport";
        $this->_controller = "adminhtml_report";
        $this->_headerText = "Filter Reports";

        parent::__construct();
        
        
    }
    protected function _prepareLayout(){
        // if(!Mage::getSingleton('admin/session')->isAllowed('banner/actions/showbutton')){
            $this->_removeButton('add');
        // }
        return parent::_prepareLayout();
    }
    protected function _prepareCollection()
	{
		$collection = Mage::getModel('filterreport/report')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
}