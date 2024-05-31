<?php

class Ccc_Salesman_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Salesman'))
            ->_title($this->__('Salesman Report'));
        $this->loadLayout()
            ->_setActiveMenu('salesman/salesman');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('salesman/adminhtml_report')->getGridHtml()
        );
    }

    protected function _isAllowed()
    {
        $aclResource = '';
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'index':
                $aclResource = 'salesman/commission/actions/index';
                break;

            case 'to':
                $aclResource = 'salesman/commission/actions/to';
                break;

            case 'from':
                $aclResource = 'salesman/commission/actions/from';
                break;

            case 'salesman':
                $aclResource = 'salesman/commission/actions/salesman';
                break;

            default:
                $aclResource = 'salesman/commission';
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}