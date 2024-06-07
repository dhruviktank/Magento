<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct(){
        $this->_controller = 'adminhtml_filemanager';
        $this->_blockGroup = 'filemanager';
        $this->_headerText = Mage::helper('filemanager')->__('File Manager');
        parent::__construct();
    }
    public function getPathArray(){
        $paths = Mage::getStoreConfig('filemanager/general/file_path');
        $paths = preg_split('/\s+/', $paths);
        return $paths;
    }
    public function getRedirectUrl(){
        return Mage::getUrl('*/*/grid');
    }
}