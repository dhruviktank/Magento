<?php

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Catalog'.DS.'ProductController.php');
class Ccc_FilterReport_Adminhtml_ProductController extends Mage_Adminhtml_Catalog_ProductController{
    public function indexAction(){
        // echo 123;
        return parent::indexAction();
    }
}