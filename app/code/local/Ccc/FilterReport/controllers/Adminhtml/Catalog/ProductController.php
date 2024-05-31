<?php

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Catalog'.DS.'ProductController.php');
class Ccc_FilterReport_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController{
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
}