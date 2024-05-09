<?php
class Ccc_Repricer_Adminhtml_MatchingController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        return $this;
    }
    public function indexAction()
    {   
        $this->_title($this->__('Repricer'));
        $this->_initAction();
        $this->renderLayout();
        // echo Mage::getSingleton('core/session')->getFormKey();
    }
    // public function editAction()
    // {
        
    //     $this->_title($this->__('repricer'))->_title($this->__('Manage Repricer'));

    //     // 1. Get ID and create model
    //     $id = $this->getRequest()->getParam('repricer_id');
    //     $model = Mage::getModel('repricer/matching');
    //     // 2. Initial checking
    //     if ($id) {
    //         $model->load($id); 
    //         if (!$model->getId()) {
    //             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('repricer')->__('This repricer no longer exists.'));
    //             $this->_redirect('*/*/');
    //             return;
    //         }
    //     }
    //     $this->_title($model->getId() ? $model->getTitle() : $this->__('New repricer'));

    //     // 3. Set entered data if was error when we do save
    //     $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
    //     if (!empty($data)) {
    //         $model->setData($data);
    //     }
    //     // 4. Register model to use later in blocks
    //     Mage::register('matching', $model);
    //     // 5. Build edit form
    //     $this->_initAction()
    //         ->_addBreadcrumb($id ? Mage::helper('repricer')->__('Edit Repricer') : Mage::helper('repricer')->__('New Repricer'), $id ? Mage::helper('repricer')->__('Edit Repricer') : Mage::helper('repricer')->__('New Repricer'));
    //     $this->renderLayout();
    // }
     public function editAction(){
        $data = $this->getRequest()->getPost();
        $repricerId = $data['repricer_id']; 
        // print_r($repricerId);die;
        $response = [];
        $response['data'] = $data;
        $model = Mage::getModel('repricer/matching')
        ->load($repricerId)
        ->addData([
            // 'repricer_id' => $repricerId,
            'competitor_url' => $data['comp_url'],
            'competitor_sku' => $data['comp_sku'],
            'competitor_price' => $data['comp_price'],
        ]);
        $vv = $model->getData();
        unset($vv['updated_date']);
        $model->setData($vv);
        // if ($model->getData('reason') == Mage::helper('repricer')::REPRICER_MATCHING_REASON_DEFAULT_NOMATCH || $model->getData('reason') == Mage::helper('repricer')::REPRICER_MATCHING_REASON_NOT_AVAILABLE ) {
            $competitorUrl = $model->getData('competitor_url');
            $competitorSku = $model->getData('competitor_sku');
            $price = $model->getData('competitor_price');
            
            if (!empty($competitorUrl) && !empty($competitorSku) && $price == 0) {
                // Set reason to 3 (Not Available)
                $model->setData('reason', Mage::helper('repricer')::REPRICER_MATCHING_REASON_NOT_AVAILABLE);
            } elseif (!empty($competitorUrl) && !empty($competitorSku) && $price != 0) {
                // Set reason to 1 (Active)
                $model->setData('reason', Mage::helper('repricer')::REPRICER_MATCHING_REASON_ACTIVE);
            }
        // }
        $model->save();
        $response['model'] = $model->getData();
        $this->getResponse()->setBody(json_encode($response));
    }
//     public function editAction()
// {
//     $repricerId = $this->getRequest()->getParam('repricer_id');

//     if (!$model->getId()) {
//         $this->getResponse()->setHeader('Content-type', 'application/json', true);
//         $this->getResponse()->setBody(json_encode(['error' => 'Repricer not found']));
//         return;
//     }

//     $editableFields = [
//         'competitor_url' => $model->getCompetitorUrl(),
//         'competitor_sku' => $model->getCompetitorSku(),
//         'competitor_price' => $model->getCompetitorPrice(),
//     ];

//     $this->getResponse()->setHeader('Content-type', 'application/json', true);
//     $this->getResponse()->setBody(json_encode($editableFields));
// }

    public function saveAction()
    {
        // Check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // Initialize model and set data
            $model = Mage::getModel('repricer/matching');

            if ($id = $this->getRequest()->getParam('repricer_id')) {
                $model->load($id);
            }

            // Set other data
            $model->setData($data);

            if ($model->getData('reason') == Mage::helper('repricer')::REPRICER_MATCHING_REASON_DEFAULT_NOMATCH || $model->getData('reason') == Mage::helper('repricer')::REPRICER_MATCHING_REASON_NOT_AVAILABLE ) {
                $competitorUrl = $model->getData('competitor_url');
                $competitorSku = $model->getData('competitor_sku');
                $price = $model->getData('competitor_price');
        
                if (!empty($competitorUrl) && !empty($competitorSku) && $price == 0) {
                    // Set reason to 3 (Not Available)
                    $model->setData('reason',Mage::helper('repricer')::REPRICER_MATCHING_REASON_NOT_AVAILABLE);
                } elseif (!empty($competitorUrl) && !empty($competitorSku) && $price != 0) {
                    // Set reason to 1 (Active)
                    $model->setData('reason', Mage::helper('repricer')::REPRICER_MATCHING_REASON_ACTIVE);
                }
            }
        

            try {
                // Save the data
                $model->save();

                // Display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('repricer')->__('The Repricer has been saved.')
                );
                // Clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('competitor_id' => $model->getId(), '_current' => true));
                    return;
                }
                // Go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('banner')->__('An error occurred while saving the repricer.')
                );
            }

            // Set form data
            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('repricer_id' => $this->getRequest()->getParam('repricer_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
    public function gridAction()
    {  
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('repricer/adminhtml_matching/grid')->getGridHtml()
        );
    }
}
