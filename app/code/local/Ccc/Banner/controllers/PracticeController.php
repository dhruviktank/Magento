<?php
class Ccc_Banner_PracticeController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
        echo "From practicetest";
        $this->loadLayout();
        $this->renderLayout();
    }
}