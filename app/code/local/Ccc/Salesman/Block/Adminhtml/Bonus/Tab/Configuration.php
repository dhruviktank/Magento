<?php
class Ccc_Salesman_Block_Adminhtml_Bonus_Tab_Configuration extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('salesman/bonus/configuration.phtml');
    }
    public function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addJs('salesman/bonus/configuration.js');
        parent::_prepareLayout();
    }
    public function getCollectionData()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $data = Mage::getModel('salesman/bonus')->load($id);
        return $data->getData();
    }
}