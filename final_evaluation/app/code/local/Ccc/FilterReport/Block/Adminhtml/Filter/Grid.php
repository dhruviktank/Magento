<?php

class Ccc_FilterReport_Block_Adminhtml_Filter_Grid extends Mage_Core_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('filterreport/grid.phtml');
    }
}