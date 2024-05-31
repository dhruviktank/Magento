<?php

class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $productName = $row->getProductName();
        $productSku = $row->getProductSku();

        return "<div>
            <div style='display: flex; justify-content: space-between; gap: 20px;'>
                <b style='align-self: flex-start;'>Product Name</b>
                <span style='align-self: flex-end;'>{$productName}</span>
            </div>
            <div style='display: flex; justify-content: space-between; gap: 20px;'>
                <b style='align-self: flex-start;'>Sku</b>
                <span style='align-self: flex-end;'>{$productSku}</span>
            </div>
        </div>
    ";
    }
}