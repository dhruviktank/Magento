<?php

class Ccc_PracticeTest_Adminhtml_PracticetestController extends Mage_Adminhtml_Controller_Action{
    protected function _initAction(){
        $this->loadLayout()
            ->_setActiveMenu('practicetest');
        return $this;
    }
    public function indexAction(){
        $this->_initAction();
        $this->renderLayout();
    }
}