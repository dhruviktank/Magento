<?php

class Ccc_FilterReport_Model_System_Report extends Mage_Adminhtml_Block_System_Config_Form_Field{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<div style="background-color:#f5f5f5; padding:10px; border:1px solid #ccc;">';
        $html .= '<h2>Custom HTML Content</h2>';
        $html .= '<p>This is your custom HTML content.</p>';
        $html .= '</div>';

        return $html;
    }

}