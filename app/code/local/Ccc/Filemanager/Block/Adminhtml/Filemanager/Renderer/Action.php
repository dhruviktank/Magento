<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $deleteUrl = $this->getUrl("*/*/deleteFile") . "?value={$row->getSystemPath()}&filename={$row->getFullpath()}";
        $downloadUrl = $this->getUrl("*/*/downloadFile") . 
            "?value={$row->getSystemPath()}&filename={$row->getFullpath()}&basename={$row->getBasename()}";

        $html = "<a href='{$deleteUrl}'>Delete</a>
        &nbsp&nbsp
        <a href={$downloadUrl}>Download</a>";
        return $html;
    }
}
