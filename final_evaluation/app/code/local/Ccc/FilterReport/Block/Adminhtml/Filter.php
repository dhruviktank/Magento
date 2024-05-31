<?php

// class Ccc_VendorInventory_Block_Adminhtml_Configuration extends Mage_Adminhtml_Block_Widget_Grid_Container
// {
//     public function __construct()
//     {
//         parent::__construct();
//         $this->_blockGroup = 'vendorinventory';
//         $this->_controller = 'adminhtml_configuration';
//         $this->_headerText = 'Configuration';
//         $this->setUseAjax(true);
//         $this->_removeButton('add');
//         // $this->
//     }
// }
// <?php

class Ccc_FilterReport_Block_Adminhtml_Filter extends Mage_Core_Block_Abstract
{
    public function __construct()
    {
        
        parent::__construct();
        $this->setTemplate('filterreport/grid.phtml');
        // parent::__construct();
        // $this->_blockGroup = "filterreport";
        // $this->_controller = "adminhtml_report";
        // $this->_headerText = "Filter Reports";
        // $this->_removeButton('add');

        
        
    }
}