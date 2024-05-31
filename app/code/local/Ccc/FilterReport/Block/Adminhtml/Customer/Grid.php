<?php

class Ccc_FilterReport_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
    protected function _prepareLayout()
    {
        $this->setChild(
            'save_report_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label' => Mage::helper('adminhtml')->__('Save Report'),
                        'onclick' => $this->getJsObjectName() . '.saveFilter()',
                        'class' => 'save_customer',
                    )
                )
        );
        return parent::_prepareLayout();
    }
    public function getSaveReportButtonHtml()
    {
        return $this->getChildHtml('save_report_button');
    }
    protected function _prepareCollection()
    {
        $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $filterReportCollection = Mage::getModel('filterreport/report')
            ->getCollection()
            ->addFieldToFilter('user_id', $userId)
            ->addFieldToFilter('report_type', 2);

        $data = $this->helper('adminhtml')
            ->prepareFilterString($filterReportCollection->getFirstItem()->getFilterData());
        $this->_setFilterValues($data);
        return parent::_prepareCollection();
    }
    public function getMainButtonsHtml()
    {
        $html = '';
        // echo Mage::getStoreConfig('filterreport/general/allow_save_report'); die;
        if ($this->getFilterVisibility()) {
            if (Mage::getStoreConfig('filterreport/general/allow_save_report') == 1)
                $html .= $this->getSaveReportButtonHtml();
            $html .= $this->getResetFilterButtonHtml();
            $html .= $this->getSearchButtonHtml();
        }
        return $html;
    }
}