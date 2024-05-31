<?php

class Ccc_Banner_Block_Adminhtml_Banner_Modules_Enable extends Mage_Adminhtml_Block_System_Config_Form_Fieldset{
    public function render(Varien_Data_Form_Element_Abstract $element){
        $this->setElement($element);
        echo 123;
        return 123;
    }
}