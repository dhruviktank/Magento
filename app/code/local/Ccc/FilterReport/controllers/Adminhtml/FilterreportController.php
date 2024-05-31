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
        // $collection = Mage::getModel('sales/order_address')->getCollection();
        // $select = $collection->getSelect();
        // $select->joinLeft(array('soa' => Mage::getModel('filterreport/report')->getTableName('filterreport/report')), 'main_table.user_id = soa.user_id', ['soa.*']);
        // $Select->join(array('soa' => Mage::getModel('sales/order_address')->getTable()), 'main_table.user_id = soa.user_id', ['']);
        // echo $collection->getSelect();
        // print_r($collection->getFirstItem());
        // echo get_class(Mage::getModel('filterreport/report'));
        $this->_initAction();
        $this->_title($this->__('Filter Report'));
        $this->renderLayout();
    }
    public function viewAction()
    {
        $this->loadLayout()->_setActiveMenu('system');
        $this->renderLayout();
    }
    public function loadAction()
    {
        // $userId = $this->getRequest()->getParam('user_id');
        // $collection = Mage::getModel('filterreport/report')->getCollection();
        // $collection->addFieldtoFilter('user_id', $userId);
        // $reports = $collection->getItems();
        // $response = [];
        // foreach ($reports as $_report) {
        //     $response[] = [
        //         'id' => $_report->getId(),
        //         'report_type' => $_report->getReportType(),
        //         'filter_data' => $_report->getFilterData()
        //     ];
        // }
        $this->getResponse()
            ->setBody($this->getLayout()->createBlock('filterreport/adminhtml_report_table')->toHtml());

    }
    public function getSavedReportAction()
    {
        $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $reportType = $this->getRequest()->getParam('reportType');
        $filterReportCollection = Mage::getModel('filterreport/report')
            ->getCollection()
            ->addFieldToFilter('user_id', $userId)
            ->addFieldToFilter('report_type', $reportType)
            ->addFieldToSelect('filter_data')
            ->setOrder('id', 'DESC')
            ->setPageSize(1)
            ->setCurPage(1);

        $decodeFdata = $filterReportCollection->getData()[0]['filter_data'];
        $this->getResponse()->setBody($decodeFdata);
    }

    public function logoutAction()
    {
        $session = Mage::getSingleton('admin/session');
        $session->unsetAll();
        $session->getCookie()->delete($session->getSessionName());
    }

    public function saveReportAction()
    {
        $adminUserId = Mage::getSingleton('admin/session')->getUser()->getId();
        $model = Mage::getModel('filterreport/report');
        $reportType = $this->getRequest()->getParam('report_type');
        $collection = $model->getCollection()
            ->addFieldtoFilter('user_id', $adminUserId)
            ->addFieldtoFilter('report_type', $reportType);
        if (sizeof($collection->getData()) > 0) {
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
        // echo $action;

        switch ($action) {
            case "showreport":
                $aclResource = "filterreport/filterreport";
                break;
            case "view":
                $aclResource = "system/reportmanager";
                break;
            default:
                $aclResource = "filterreport";
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}