<?php

class Ccc_Banner_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('banner/banner')
        ;

        return $this;
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('Banner'));
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }
    public function editAction()
    {
        $id = $this->getRequest()->getParam('banner_id');
        $model = Mage::getModel('banner/banner');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('This block no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Block'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('banner', $model);
        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Banner') : $this->__('New banner'), $id ? $this->__('Edit Banner') : $this->__('New Banner'))
            ->renderLayout();
    }
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // banner image uploading
            $type = 'image';

            if (isset($data[$type]['delete'])) {
                Mage::helper('banner')->deleteImageFile($data[$type]['value']);
            }
            $image = Mage::helper('banner')->uploadBannerImage($type);

            // init model and set data
            $id = $this->getRequest()->getParam('banner_id');
            $model = Mage::getModel('banner/banner')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('This Banner no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            if ($image || (isset($data[$type]['delete']) && $data[$type]['delete'])) {
                if ($model->getImage()) {
                    Mage::helper('banner')->deleteImageFile($model->getImage());
                }
                $data[$type] = $image;
            } else {
                unset($data[$type]);
            }
            $model->setData($data);

            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('The block has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('banner_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('banner_id' => $this->getRequest()->getParam('banner_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * banner delete action
     * 
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('banner_id')) {
            $title = "";
            try {
                // init model and delete
                $model = Mage::getModel('banner/banner');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('banner')->__('The banner has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('banner_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('banner')->__('Unable to find a block to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds)) {
            $this->_getSession()->addError($this->__('Please select banner(s).'));
        } else {
            if (!empty($bannerIds)) {
                try {
                    foreach ($bannerIds as $bannerId) {
                        $product = Mage::getSingleton('banner/banner')->load($bannerId);
                        $product->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($bannerIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $bannerIds = (array) $this->getRequest()->getParam('banner');
        $status = (int) $this->getRequest()->getParam('status');
        try {
            foreach ($bannerIds as $bannerId) {
                Mage::getModel('banner/banner')->load($bannerId)
                    ->setData('status', $status)->save();
            }
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($bannerIds))
            );
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the banner(s) status.'));
            // ->addException($e, $e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());

        switch ($action) {
            case "showbutton":
                $aclResource = "banner/actions/showbutton";
                break;
            case "fieldlimit":
                $aclResource = "banner/actions/fieldlimit";
                break;
            case 'field/name':
                $aclResource = 'banner/field/name';
                break;
            default:
                $aclResource = "banner";
                break;
        }
        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}