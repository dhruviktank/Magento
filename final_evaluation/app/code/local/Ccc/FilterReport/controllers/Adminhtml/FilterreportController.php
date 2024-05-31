<?php

class Ccc_FilterReport_Adminhtml_FilterreportController extends Mage_Adminhtml_Controller_Action
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
    public function viewAction(){
        $this->_initAction();
        $this->renderLayout();
    }

    public function saveReportAction(){
        // print_r($this->getRequest()->getPost('elements'));
        $adminUserId = Mage::getSingleton('admin/session')->getUser()->getId();
        $model = Mage::getModel('filterreport/report');
        $reportType = $this->getRequest()->getParam('report_type');
        $collection = $model->getCollection()
            ->addFieldtoFilter('user_id', $adminUserId)
            ->addFieldtoFilter('report_type', $reportType);
        if(sizeof($collection->getData()) > 0){
            $model = $collection->getFirstItem();
        }
        $model->addData([
            'user_id' => $adminUserId,
            'filter_data' => $this->getRequest()->getParam('filters'),
            'report_type' => $reportType,
        ]);

        $model->save();
        
        $this->getResponse()->setBody($model->getFilterData());
    }

    public function massStatusAction()
    {
        $reportIds = (array) $this->getRequest()->getParam('report');
        $status = (int) $this->getRequest()->getParam('status');
        try {
            foreach ($reportIds as $_reportId) {
                Mage::getModel('filterreport/report')->load($_reportId)
                    ->setData('is_active', $status)->save();
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($reportIds))
            );
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the report(s) status.'));
        }

        $this->_redirect('*/*/index');
    }
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        // echo '<pre>';
        // print_r(Mage::getSingleton('admin/session')->getAcl());

        switch ($action) {
            case "showgrid":
                $aclResource = "filterreport/actions/showgrid";
                break;
            case "showreport":
                $aclResource = "filterreport/actions/showreport";
                break;
            case "index":
                $aclResource = "filterreport/actions/index";
                break;
            default:
                $aclResource = "filterreport";
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}