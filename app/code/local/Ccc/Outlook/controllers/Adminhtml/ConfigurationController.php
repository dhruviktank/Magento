<?php

class Ccc_Outlook_Adminhtml_ConfigurationController extends Mage_Adminhtml_Controller_Action{
    protected function _initAction(){

        $this->loadLayout()
            ->_setActiveMenu('outlook/configuration');
        return $this;
    }
    
    public function indexAction(){
        $this->_initAction()
            ->_title(Mage::helper('outlook')->__('Configuration'));
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function loginAction(){
        $arg = $this->getRequest()->getParam('id');
        $user = Mage::getModel('outlook/configuration')->load($arg);
        $clientId = $user->getClientId();
        $scope = 'https://graph.microsoft.com/Mail.Read';
        $redirectUri = Mage::getUrl('outlook/callback/token',['id'=>$arg]);
        $authorizationEndpoint = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";
        $authUrl = "$authorizationEndpoint?client_id={$clientId}&response_type=code&redirect_uri=$redirectUri&scope={$scope}";
        $this->_redirectUrl($authUrl);
    }

    public function storeAccessCodeAction(){}
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

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('configuration', $model);
        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Configuration') : $this->__('New Configuration'), $id ? $this->__('Edit Configuration') : $this->__('New Configuration'))
            ->renderLayout();
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
    public function saveEventAction()
    {
        $params = json_decode($this->getRequest()->getRawBody(), true);
        $lastGroupId = Mage::getModel('outlook/event')->getCollection()
            ->setOrder('group_id', 'DESC')
            ->getFirstItem()
            ->getGroupId();

        $newGroupId = ($lastGroupId) ? $lastGroupId + 1 : 1;
        if (isset($params['events']) && is_array($params['events'])) {
            try {
                foreach ($params['events'] as $eventData) {

                    foreach ($eventData as $_conditions) {
                        $eventModel = Mage::getModel('outlook/event');
                        $_conditions['group_id'] = $newGroupId;
                        $eventModel->setData($_conditions);
                        $eventModel->save();
                    }
                    $newGroupId++;
                }
                $this->getResponse()->setBody(json_encode(['status' => 'success', 'message' => 'Events saved successfully']));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->getResponse()->setBody(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
            }
        } else {
            $this->getResponse()->setBody(json_encode(['status' => 'error', 'message' => 'Invalid data']));
        }
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