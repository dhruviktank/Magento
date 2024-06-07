<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('filemanager');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        if ($this->getRequest()->getParam('isAjax')) {
            $collection = Mage::getModel('filemanager/filemanager');
            $basePath = str_replace('/', '\\', $this->getRequest()->getParam('folderPath'));
            $collection->addTargetDir($basePath);
            $this->setCollection($collection);
            parent::_prepareCollection();
        }
    }
    protected function _prepareColumns()
    {
        $this->addColumn(
            'created_date',
            array(
                'header' => Mage::helper('filemanager')->__('Created Date'),
                'type' => 'timestamp',
                'index' => 'created_date',
            )
        );
        $this->addColumn(
            'dirname',
            array(
                'header' => Mage::helper('filemanager')->__('Folder Path'),
                'type' => 'text',
                'index' => 'dirname',
            )
        );
        $this->addColumn(
            'filename',
            array(
                'header' => Mage::helper('filemanager')->__('File Name'),
                'type' => 'text',
                'index' => 'filename',
                'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Renderer_Edit'
            )
        );
        $this->addColumn(
            'extension',
            array(
                'header' => Mage::helper('filemanager')->__('Extension'),
                'type' => 'text',
                'index' => 'extension',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header' => Mage::helper('filemanager')->__('Action'),
                'filter' => false,
                'sortable' => false,
                'renderer' => 'Ccc_Filemanager_Block_Adminhtml_Filemanager_Renderer_Action'
            )
        );
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}
