<?php
class Ccc_Repricer_Block_Adminhtml_Matching_Grid_Renderer_Column extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        // Render competitor information
        $productId = $row->getData('product_id');
        $reason = $row->getData('reason');
        $items = Mage::getModel('repricer/matching')->getCollection()->addFieldToFilter('product_id', $productId);
        $columnIndex = $this->getColumn()->getIndex();
        // $itemId = $row->getId();
        // $editUrl = $this->getUrl('*/*/edit', array('repricer_id' => $itemId));

        $items->getSelect()
            ->join(
                array('cpev' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
                'cpev.competitor_id = main_table.competitor_id',
                ['cpev.name AS competitor_name']
            );

        $this->getFilter($items);
        $reason = Mage::helper('repricer')->getReasonOptionArray();
        $output = "<table style='border: 0;'>";

        switch ($columnIndex) {
            case 'competitor_name':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23vh>";
                    $output .= "<td width = 150px>";
                    $output .= $item->getCompetitorName();
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'competitor_url':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23vh>";
                    $output .= "<td class='editable {$item->getId()}' data-field='competitor_url' data-item-id='{$item->getId()}'>";
                    $output .= $item->getCompetitorUrl();
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'competitor_sku':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23>";
                    $output .= "<td class='editable {$item->getId()} data-field='competitor_sku' data-item-id='{$item->getId()}'>";
                    $output .= $item->getCompetitorSku();
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'competitor_price':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23vh>";
                    $output .= "<td class='editable {$item->getId()} data-field='competitor_price' data-item-id='{$item->getId()}'>";
                    $output .= $item->getCompetitorPrice();
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'reason':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23vh>";
                    $output .= "<td width = 10px>";
                    $output .= $reason[$item->getReason()];
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'updated_date':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $output .= "<tr height=23vh>";
                    $output .= "<td width = 150px>";
                    $output .= $item->getUpdatedDate();
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;
            case 'edit':
                $output .= "<table style='border: 0;'>";
                foreach ($items as $item) {
                    $formkey = Mage::getSingleton('core/session')->getFormKey();
                    $output .= "<tr height=23vh>";
                    $output .= "<td width = 50px class='editable' data-field='edit_link' data-item-id='{$item->getId()}' data-form-key='{$formkey}' data-edit-url='{$this->getUrl('*/*/edit', array('repricer_id' => $item->getId()))}'>";
                    $output .= "<a href='#' class='edit-row'>Edit</a>";
                    $output .= "</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
                break;

        }

        return $output;
    }


    public function getFilter($items)
    {
        $request = $this->getColumn()->getGrid()->getRequest();
        $filterEncoded = $request->getParam('filter');
        $filterDecoded = base64_decode($filterEncoded);
        parse_str($filterDecoded, $filterArray);

        foreach ($filterArray as $key => $value) {
            switch ($key) {
                case 'competitor_name':
                    $this->applyFilter($items, 'main_table.competitor_id', $value);
                    break;
                case 'competitor_url':
                    $this->applyFilter($items, 'main_table.competitor_url', $value);
                    break;
                case 'competitor_sku':
                    $this->applyFilter($items, 'main_table.competitor_sku', $value);
                    break;
                case 'competitor_price':
                    $from = isset($value['from']) ? $value['from'] : 0;
                    $to = isset($value['to']) ? $value['to'] : null;
                    if (!empty($from)) {
                        $items->getSelect()->where("main_table.competitor_price >= ?", $from);
                    }
                    if (!empty($to)) {
                        $items->getSelect()->where("main_table.competitor_price <= ?", $to);
                    }
                    break;
                case 'reason':
                    $this->applyFilter($items, 'main_table.reason', $value);
                    break;
                case 'updated_date':
                    $dateFilter = [];
                    if (isset($value['from'])) {
                        $dateFilter['gteq'] = date('Y-m-d H:i:s', strtotime($value['from']));
                    }
                    if (isset($value['to'])) {
                        $dateFilter['lteq'] = date('Y-m-d 23:59:59', strtotime($value['to']));
                    }
                    if (!empty($dateFilter)) {
                        $items->addFieldToFilter('main_table.updated_date', $dateFilter);
                    }
                    break;


            }
        }
    }

    private function applyFilter($items, $column, $filterValue)
    {
        if ($filterValue) {
            $items->getSelect()->where("$column LIKE ?", "%{$filterValue}%");
        }
    }

}

// public function getFilter($items){
//     $competitorNameFilter = '';
//     $competitorUrlFilter = '';
//     $competitorSkuFilter = '';
//     $competitorPriceFilter = '';
//     $competitorReason = '';

//     $request = $this->getColumn()->getGrid()->getRequest();
//     $filterEncoded = $request->getParam('filter');
//     $filterDecoded = base64_decode($filterEncoded);
//     parse_str($filterDecoded, $filterArray);

//     //filter for competitor_name
//     if (isset($filterArray['competitor_name'])) {
//         $competitorNameFilter = $filterArray['competitor_name'];
//     }
//     if ($competitorNameFilter) {
//         $items->getSelect()->where("main_table.competitor_id LIKE ?", "%{$competitorNameFilter}%");
//     }

//     //filter for competitor_url
//     if (isset($filterArray['competitor_url'])) {
//         $competitorUrlFilter = $filterArray['competitor_url'];
//     }
//     if ($competitorUrlFilter) {
//         $items->getSelect()->where("main_table.competitor_url LIKE ?", "%{$competitorUrlFilter}%");
//     }

//     //filter for competitor_sku
//     if (isset($filterArray['competitor_sku'])) {
//         $competitorSkuFilter = $filterArray['competitor_sku'];
//     }
//     if ($competitorSkuFilter) {
//         $items->getSelect()->where("main_table.competitor_sku LIKE ?", "%{$competitorSkuFilter}%");
//     }

//     //filter for competitor_price
//     if (isset($filterArray['competitor_price'])) {
//         $competitorPriceFilter = $filterArray['competitor_price'];
//     }
//     if ($competitorPriceFilter) {
//         $items->getSelect()->where("main_table.competitor_price LIKE ?", "%{$competitorPriceFilter}%");
//     }

//     //filter for reason
//     if (isset($filterArray['reason'])) {
//         $competitorReason = $filterArray['reason'];
//     }
//     if ($competitorReason) {
//         $items->getSelect()->where("main_table.reason LIKE ?", "%{$competitorReason}%");
//     }

// }