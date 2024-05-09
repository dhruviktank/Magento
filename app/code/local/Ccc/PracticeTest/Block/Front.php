<?php

class Ccc_PracticeTest_Block_Front extends Mage_Core_Block_Template
{
    public function _prepareLayout(){
        $this->setId('practiceFrontBlock');
        return parent::_prepareLayout();
    }
}