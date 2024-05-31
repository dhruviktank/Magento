<?php

class Ccc_Salesman_Adminhtml_BonusController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Salesman'))
            ->_title($this->__('Salesmen Bonus'));
        $this->loadLayout()
            ->_setActiveMenu('salesman/salesman');
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('salesman/adminhtml_bonus')->getGridHtml()
        );
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('entity_id');
            $model = Mage::getModel('salesman/bonus')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('salesman')->__('This configuration no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            echo "<pre>";
            unset($data['form_key']);
            if (empty($model->getData())) {
                unset($data['entity_id']);
            }
            print_r($model->setData($data));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('salesman')->__('The configuration has been saved.'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('config_id' => $this->getRequest()->getParam('config_id')));
            }
        }
        $this->_redirect('*/*/league', array('id' => $model->getId()));
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('config_id');
        $model = Mage::getModel('salesman/bonus');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bonus')->__('This configuration no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Block'));
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        Mage::register('bonus_configuration', $model);

        $this->loadLayout();
        // $this->loadLayout()->_setActiveMenu('salesman/bonus_configuration');
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('configuration');
    }

    public function configurationAction()
    {
        $this->loadLayout();
        $this->loadLayout()->_setActiveMenu('salesman/bonus_configuration');

        $this->_addContent(
            $this->getLayout()->createBlock('salesman/adminhtml_bonus_tab_configuration')
        );
        Mage::register('bonus_configuration', $this->getLayout()->createBlock('salesman/adminhtml_bonus_tab_configuration'));
        $this->renderLayout();
    }

    public function leagueAction()
    {
        $this->loadLayout();

        $this->_addContent(
            $this->getLayout()->createBlock('salesman/adminhtml_bonus_tab_league')
        );
        Mage::register('bonus_league', 'bonus');
        $this->renderLayout();
    }

    public function dataAction()
    {
        $cardData = $this->getRequest()->getParams();
        $leagueData = json_decode($cardData['data']);
        $configId = $cardData['config_id'];
        $model = Mage::getModel('salesman/bonusLeagueUser');
        $existingData = $model->getCollection()
            ->addFieldToFilter('configuration_id', $configId)
            ->addFieldToSelect('user_id')
            ->addFieldToSelect('entity_id');

        $existingDataArray = [];
        foreach ($existingData as $item) {
            $existingDataArray[$item->getUserId()] = $item->getEntityId();
        }
        foreach ($leagueData as $leagueNumber => $league) {
            $leagueNumber = str_replace('league_', '', $leagueNumber);
            $bonusArray = [];
            foreach ($league->bonus as $username => $bonus) {
                $bonusArray[] = $bonus;
            }
            $rank = 1;

            foreach ($league->users as $username) {
                $userId = $this->getUserIdByUsername($username);
                if ($userId) {
                    $bonus = isset($bonusArray[$rank - 1]) ? $bonusArray[$rank - 1] : 0;
                    $data = [
                        'configuration_id' => $configId,
                        'league_number' => $leagueNumber,
                        'user_id' => $userId,
                        'rank' => $rank,
                        'bonus' => $bonus,
                    ];
                    try {
                        if (isset($existingDataArray[$userId])) {
                            $entityId = $existingDataArray[$userId];
                            $existingRecord = $model->load($entityId);
                            $existingRecord->setData('bonus', $data['bonus']);
                            $existingRecord->setData('league_number', $data['league_number']);
                            $existingRecord->setData('rank', $data['rank']);
                            $existingRecord->save();
                        } else {
                            $newRecord = Mage::getModel('salesman/bonusLeagueUser');
                            $newRecord->setData($data);
                            $newRecord->save();
                        }
                    } catch (Exception $e) {
                        Mage::log('Error saving data: ' . $e->getMessage());
                    }
                    $rank++;
                }
            }
        }
    }

    private function getUserIdByUsername($username)
    {
        $user = Mage::getModel('admin/user')->getCollection()
            ->addFieldToFilter('username', $username)
            ->getFirstItem();
        if ($user->getId()) {
            return $user->getId();
        }
        return null;
    }
}