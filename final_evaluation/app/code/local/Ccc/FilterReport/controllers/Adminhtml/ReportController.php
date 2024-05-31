<?php

class Ccc_FilterReport_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('filterreport/filterreport')
        ;

        return $this;
    }
    public function indexAction()
    {
        // echo get_class(Mage::getModel('filterreport/report'));
        $this->_initAction();
        $this->_title($this->__('Filter Report'));
        $this->renderLayout();
    }
}