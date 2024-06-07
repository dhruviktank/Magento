<?php

class Ccc_Outlook_Adminhtml_ConfigurationController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {

        $this->loadLayout()
            ->_setActiveMenu('outlook/configuration');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->_title(Mage::helper('outlook')->__('Configuration'));
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function loginAction()
    {
        $configId = $this->getRequest()->getParam('id');
        if ($configId) {
            $userConfig = Mage::getModel('outlook/configuration')->load($configId);
            $authUrl = Mage::getModel('outlook/api')
                ->setUserConfig($userConfig)
                ->getAuthorizationUrl();
            $this->_redirectUrl($authUrl);
        }
    }
    public function editAction()
    {
        $id = $this->getRequest()->getParam('configuration_id');
        $model = Mage::getModel('outlook/configuration');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('outlook')->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getConfigurationId() ? $model->getTitle() : $this->__('New Block'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('configuration', $model);
        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Configuration') : $this->__('New Configuration'), $id ? $this->__('Edit Configuration') : $this->__('New Configuration'))
            ->renderLayout();
    }
    public function fetchEventAction()
    {
        $configId = $this->getRequest()->getParam('configId');
        $events = Mage::getModel('outlook/event')->getCollection()
            ->addFieldtoFilter('configuration_id', $configId)
            ->getData();
        $this->getResponse()->setBody(json_encode($events));
    }

    public function saveEventAction()
    {
        $request = $this->getRequest()->getParams();
        if (isset($request['data'])) {
            // print_r($request); die;
            $existingEventIds = Mage::getModel('outlook/event')->getCollection()
            ->addFieldToFilter('configuration_id', $request['configId'])
            ->getAllIds();
            // print_r($existingEvents); die;
            $data = json_decode($request['data']);
            $submittedEventIds = [];
            foreach ($data as $_event) {
                foreach ($_event->condition as $_condition) {
                    $event = Mage::getModel('outlook/event');
                    if (isset($_condition->eventId) && !empty($_condition->eventId)){
                        $event->load($_condition->eventId);
                        $submittedEventIds[] = $_condition->eventId;
                    }
                    $event->setField($_condition->field);
                    $event->setCondition($_condition->condition);
                    $event->setValue($_condition->value);
                    $event->setEvent($_condition->event);
                    $event->setConfigurationId($_event->configId);
                    $event->setGroupId($_event->groupId);
                    $event->save();
                }
            }
            foreach ($existingEventIds as $eventId) {
                if (!in_array($eventId, $submittedEventIds)) {
                    $event = Mage::getModel('outlook/event')->load($eventId);
                    $event->delete();
                }
            }
        }
    }
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('configuration_id');
            $model = Mage::getModel('outlook/configuration')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('outlook')->__('This Configuration no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            $model->setData($data);
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('outlook')->__('The block has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('configuration_id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('configuration_id' => $this->getRequest()->getParam('configuration_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }


    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('configuration_id')) {
            $title = "";
            try {
                $model = Mage::getModel('outlook/configuration');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('outlook')->__('The Configuration has been deleted.'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('configuration_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('outlook')->__('Unable to find a block to delete.'));
        $this->_redirect('*/*/');
    }
}