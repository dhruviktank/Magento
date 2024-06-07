<?php
class Ccc_Filemanager_Adminhtml_FilemanagerController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('filemanager');
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Filemanager'));
        $this->_initAction();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()
            ->setBody(
                $this->getLayout()
                    ->createBlock('filemanager/adminhtml_filemanager_grid')
                    ->toHtml()
            );
    }
    public function renameAction()
    {
        $value = $this->getRequest()->getParam('value');
        $oldFilename = $this->getRequest()->getParam('oldFilename');
        $newFilename = $this->getRequest()->getParam('newFilename');

        // Convert directory separators for cross-platform compatibility
        $oldFilename = str_replace('/', DS, $oldFilename);
        $newFilename = str_replace('/', DS, $newFilename);

        $oldFilePath = Mage::getBaseDir() . DS . $oldFilename;
        $newFilePath = dirname($oldFilePath) . DS . $newFilename;

        try {
            if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
                rename($oldFilePath, $newFilePath);
                Mage::getSingleton('core/session')->addSuccess('File renamed successfully.');
                $this->getResponse()->setBody('Success');
            } else {
                Mage::getSingleton('core/session')->addError('Error occurred while renaming. Either the old file does not exist, or the new file already exists.');
                $this->getResponse()->setBody('Failure');
            }
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->getResponse()->setBody('Failure');
        }
        $this->_redirect('*/*/index', ['_query' => ['isAjax' => true, 'folderPath' => $value]]);
    }

    public function sampleAction()
    {

    }
    public function deleteFileAction()
    {
        $value = $this->getRequest()->getParam('value');
        $filename = $this->getRequest()->getParam('filename');
        if (file_exists($filename)) {
            unlink($filename);
            Mage::getSingleton('core/session')->addSuccess('File Deleted Successfully.');
        } else {
            Mage::getSingleton('core/session')->addError('Error occured while deleting.');
        }
        $this->_redirect('*/*/index', ['_query' => ['isAjax' => true, 'folderPath' => $value]]);
    }

    public function downloadFileAction()
    {
        $value = $this->getRequest()->getParam('value');
        $filename = $this->getRequest()->getParam('filename');
        $basename = $this->getRequest()->getParam('basename');
        $filename = Mage::getBaseDir() . DS . $filename;

        if (file_exists($filename)) {
            $this->_prepareDownloadResponse($basename, array('type' => 'filename', 'value' => $filename));
        }
        $this->_redirect('*/*/index', ['_query' => ['isAjax' => true, 'folderPath' => $value]]);
    }
}
