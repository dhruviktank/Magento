<?php

class Ccc_Banner_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = "banner";
        $this->_controller = "adminhtml_banner";
        $this->_headerText = "Banner";

        parent::__construct();
        
        
    }
    protected function _prepareLayout(){
        if(!Mage::getSingleton('admin/session')->isAllowed('banner/actions/showbutton')){
            $this->_removeButton('add');
        }
        return parent::_prepareLayout();
    }
    protected function _prepareCollection()
	{
		$collection = Mage::getModel('cms/block')->getCollection();
		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
}