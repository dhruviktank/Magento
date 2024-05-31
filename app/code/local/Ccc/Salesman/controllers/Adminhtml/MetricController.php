<?php
class Ccc_Salesman_Adminhtml_MetricController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('Salesman'))
            ->_title($this->__('Manage Salesman'));

        $this->loadLayout()
            ->_setActiveMenu('salesman/salesman')
            ->_addBreadcrumb(Mage::helper('salesman')->__('Salesman'), Mage::helper('salesman')->__('Salesman'))
            ->_addBreadcrumb(Mage::helper('salesman')->__('Manage Salesman'), Mage::helper('salesman')->__('Manage Salesman'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Salesman'))
            ->_title($this->__('Manage Salesman'));

        $id = $this->getRequest()->getParam('entity_id');
        $model = Mage::getModel('salesman/metric');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('salesman')->__('This salesman no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }
        
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Salesman'));
        
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        var_dump($data);
        echo 123;
        // die;
        if (!empty($data)) {
            $model->setData($data);
        }
        
        Mage::register('salesman', $model);

        $this->loadLayout()
            ->_setActiveMenu('salesman/salesman')
            ->_addBreadcrumb(Mage::helper('salesman')->__('Salesman'), Mage::helper('salesman')->__('Salesman'))
            ->_addBreadcrumb(Mage::helper('salesman')->__('Manage Salesman'), Mage::helper('salesman')->__('Manage Salesman'))
            ->_addBreadcrumb(
                $id ? Mage::helper('salesman')->__('Edit Salesman')
                    : Mage::helper('salesman')->__('New Salesman'),
                $id ? Mage::helper('salesman')->__('Edit Salesman')
                    : Mage::helper('salesman')->__('New Salesman')
            );
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('salesman/metric');

            if ($id = $this->getRequest()->getParam('entity_id')) {
                $model->load($id);
            }
            $model->setData($data);

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('salesman')->__('The salesman has been saved.')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('entity_id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }

            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/edit', array('entity_id' => $this->getRequest()->getParam('entity_id')));
            return;
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('entity_id')) {
            try {
                $model = Mage::getModel('salesman/metric');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('salesman')->__('The salesman has been deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}