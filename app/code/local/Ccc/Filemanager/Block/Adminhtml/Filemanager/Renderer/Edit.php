<?php

class Ccc_Filemanager_Block_Adminhtml_Filemanager_Renderer_Edit extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $data = json_encode($row->getData());
        $url = $this->getUrl('*/*/rename');
        $html = "<div data='{$data}' onClick='inlineEdit(this)' data-url='{$url}'>".$row->getFilename()."</div>";
        return $html;
    }
}
