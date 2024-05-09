<?php

class Ccc_Banner_BannerController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo "from banner";
        $this->loadLayout();
        $this->renderLayout();
    }
}