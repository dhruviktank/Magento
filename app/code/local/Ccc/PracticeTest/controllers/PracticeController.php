<?php
class Ccc_PracticeTest_PracticeController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
        echo "From banner";
        $this->loadLayout();
        $this->renderLayout();
    }
}