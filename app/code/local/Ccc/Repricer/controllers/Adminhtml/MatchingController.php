<?php
class Ccc_Repricer_Adminhtml_MatchingController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Repricer'));
        $this->_initAction();
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }
    public function editAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repricerId = $this->getRequest()->getPost('itemId');
            $editedData = $this->getRequest()->getPost('editedData');
            $model = Mage::getModel('repricer/matching');

            if ($repricerId) {
                $model->addData(['repricer_id' => $repricerId]);
                // $model->addData(['reason' => $editedData['reason']]);
                foreach ($editedData as $field => $value) {
                    $model->addData(([$field => $value]));
                }
                $url = $model->getCompetitorUrl();
                $sku = $model->getCompetitorSku();
                $price = $model->getCompetitorPrice();
                switch ($model->getReason()) {
                    case $model::CONST_REASON_NO_MATCH:
                    case $model::CONST_REASON_ACTIVE:
                        if (!empty($url) && !empty($sku)) {
                            if ($price > 0) {
                                $model->addData(['reason' => $model::CONST_REASON_ACTIVE]);
                            } else {
                                $model->addData(['reason' => $model::CONST_REASON_NOT_AVAILABLE]);
                            }
                        } else {
                            $model->addData(['reason' => $model::CONST_REASON_NO_MATCH]);
                        }
                        break;
                    case $model::CONST_REASON_NOT_AVAILABLE:
                        $model->addData(['competitor_price' => 0.0]);
                        break;
                    case $model::CONST_REASON_WRONG_MATCH:
                        $repricer = Mage::getModel('repricer/matching')->load($repricerId);
                        if (!empty($url) && !empty($sku)) {
                            if (($repricer->getReason() == $model::CONST_REASON_WRONG_MATCH) && (($repricer->getCompetitorUrl() != $model->getCompetitorUrl()) || ($repricer->getCompetitorSku() != $model->getCompetitorSku()))) {
                                $model->addData(['competitor_price' => 0.0]);
                                $model->addData(['reason' => $model::CONST_REASON_NOT_AVAILABLE]);
                            }
                        } else {
                            $model->addData(['reason' => $model::CONST_REASON_NO_MATCH]);
                        }
                        break;
                }
                $model->save();
            }

            // die;
            $response = array(
                'success' => true,
                'message' => 'Data saved successfully'
            );
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(json_encode($response));
        }
    }
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('repricer/adminhtml_matching/grid')->getGridHtml()
        );
    }
    public function massReasonAction()
    {
        echo "<pre>";
        $pc_comb = $this->getRequest()->getParam('pc_comb');
        $reason = $this->getRequest()->getParam('reason');
        print_r($pc_comb);
        die;
        $matching = Mage::getModel('repricer/matching');
        $counter = 0;
        try {
            foreach ($pc_comb as $pc) {
                $parts = explode("-", $pc, 2);
                $pId = $parts[0];
                $cId = $parts[1];
                $dataArray = $matching->getCollection()
                    ->addFieldToFilter('product_id', $pId)
                    ->addFieldToFilter('competitor_id', $cId)
                    ->getFirstItem();

                $matching->load($dataArray->getRepricerId());
                $dbReason = $dataArray->getReason();
                if ($dbReason != $reason) {
                    $matching->addData(['reason' => $reason]);
                    // print_r($matching->getData());
                    $matching->save();
                    $counter++;
                }
            }
            // die;
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been enabled.', $counter)
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'edit':
                $aclResource = 'catalog/repricer/repricer/actions/edit';
                break;
            case 'grid':
                $aclResource = 'catalog/repricer/repricer/actions/grid';
                break;
            default:
                $aclResource = 'catalog/repricer/repricer/actions/index';
                break;

        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }

    // public function saveAction()
    // {
    //     // Check if data sent
    //     if ($data = $this->getRequest()->getPost()) {
    //         // Initialize model and set data
    //         $model = Mage::getModel('repricer/matching');

    //         if ($id = $this->getRequest()->getParam('repricer_id')) {
    //             $model->load($id);
    //         }
    //         $model->setData($data);
    //         if ($model->getReason() == $model::CONST_REASON_NO_MATCH || $model->getReason() == $model::CONST_REASON_NOT_AVAILABLE) {
    //             if (!empty($model->getCompetitorUrl()) && !empty($model->getCompetitorSku()) && ($model->getCompetitorPrice() == 0.0)) {
    //                 $model->addData(['reason' => $model::CONST_REASON_NOT_AVAILABLE]);
    //             }
    //             if (!empty($model->getCompetitorUrl()) && !empty($model->getCompetitorSku()) && ($model->getCompetitorPrice() > 0.0)) {
    //                 $model->addData(['reason' => $model::CONST_REASON_ACTIVE]);
    //             }
    //         }

    //         // Set other data

    //         try {
    //             // Save the data
    //             $model->save();

    //             // Display success message
    //             Mage::getSingleton('adminhtml/session')->addSuccess(
    //                 Mage::helper('repricer')->__('The Repricer has been saved.')
    //             );
    //             // Clear previously saved data from session
    //             Mage::getSingleton('adminhtml/session')->setFormData(false);
    //             // Check if 'Save and Continue'
    //             if ($this->getRequest()->getParam('back')) {
    //                 $this->_redirect('*/*/edit', array('competitor_id' => $model->getId(), '_current' => true));
    //                 return;
    //             }
    //             // Go to grid
    //             $this->_redirect('*/*/');
    //             return;
    //         } catch (Mage_Core_Exception $e) {
    //             $this->_getSession()->addError($e->getMessage());
    //         } catch (Exception $e) {
    //             $this->_getSession()->addException(
    //                 $e,
    //                 Mage::helper('banner')->__('An error occurred while saving the repricer.')
    //             );
    //         }

    //         // Set form data
    //         $this->_getSession()->setFormData($data);
    //         $this->_redirect('*/*/edit', array('repricer_id' => $this->getRequest()->getParam('repricer_id')));
    //         return;
    //     }
    //     $this->_redirect('*/*/');
    // }
    // public function deleteAction()
    // {
    //     if ($id = $this->getRequest()->getParam('repricer_id')) {
    //         $title = "";
    //         try {
    //             $model = Mage::getModel('repricer/matching');
    //             $model->load($id);
    //             $title = $model->getTitle();
    //             $model->delete();
    //             Mage::getSingleton('adminhtml/session')->addSuccess(
    //                 Mage::helper('repricer')->__('The Repricer has been deleted.')
    //             );
    //             Mage::dispatchEvent('adminhtml_matching_on_delete', array('title' => $title, 'status' => 'success'));
    //             $this->_redirect('*/*/');
    //             return;
    //         } catch (Exception $e) {
    //             Mage::dispatchEvent('adminhtml_matching_on_delete', array('title' => $title, 'status' => 'fail'));
    //             Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    //             $this->_redirect('*/*/edit', array('page_id' => $id));
    //             return;
    //         }
    //     }
    //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('Unable to find a competitor to delete.'));
    //     $this->_redirect('*/*/');
    // }

}