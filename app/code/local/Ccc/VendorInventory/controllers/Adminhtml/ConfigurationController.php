<?php

class Ccc_VendorInventory_Adminhtml_ConfigurationController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('vendorinventory/configuration')
        ;
        return $this;
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->_title($this->__('Configuration'));
        $this->renderLayout();
    }
    public function getheadersAction()
    {
        $response = array();
        try{
            if (isset($_FILES['header-file'])) {
                $response['headers'] = $this->processCsvFile($_FILES['header-file']['tmp_name']);
            }
         }catch(Exception $e){
             $response['error'] = $e;
         }
        $this->getResponse()->setBody(json_encode($response));
    }
    public function getconfigurationAction()
    {
        $brandId = $this->getRequest()->getPost('brandId');
        $response = array();
        $columns = [
            'config_column_id' => 'config_column.id',
            'config_brand_id' => 'main_table.brand_id',
            'column_config' => 'config_column.brand_column_configuration',
            'brand_headers' => 'main_table.headers'
        ];
        $collection = Mage::getModel('vendorinventory/configuration')->getCollection();
        $collection->join(
            [
                'config_column' => 'vendorinventory/configuration_column'
            ],
            'main_table.id = config_column.brand_configuration_id'
        );
        $collection->getSelect()->reset('columns')->columns($columns);
        $data = $collection->addFieldtoFilter('main_table.brand_id', $brandId)->getFirstItem();
        if ($data->getBrandHeaders()) {
            $response['headers'] = explode(',', $data->getBrandHeaders());
            $response['config'] = json_decode($data->getColumnConfig());
            $response['configId'] = (int) $data->getConfigColumnId();
        }
        $this->getResponse()->setBody(json_encode($response));
    }
    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();
        $configuration = json_decode($postData['configuration']);
        foreach ($configuration as $key => $val) {
            $brandId = $key;
            $columnConfig = $val;
        }
        if ($this->getRequest()->getPost('configId')) {
            Mage::getModel('vendorinventory/configuration_column')
                ->load($this->getRequest()->getPost('configId'))
                ->setData('brand_column_configuration', json_encode($columnConfig))
                ->save();
        } else {
            try {
                $response = [];
                $data = [
                    'brand_id' => $brandId, 
                    'headers' => $postData['headers']
                ];
                $brandConfig = Mage::getModel('vendorinventory/configuration')->setData($data)->save();
                $data = [
                    'brand_configuration_id' => $brandConfig->getId(),
                    'brand_column_configuration' => json_encode($columnConfig),
                ];
                Mage::getModel('vendorinventory/configuration_column')->setData($data)->save();
                $this->getResponse()->setBody(json_encode($response));
            } catch (Exception $e) {
                $this->getResponse()->setBody('error: ' . $e->getMessage());
            }
        }
    }
    private function processCsvFile($csvFilePath)
    {
        $headers = array();
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            if (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $headers = $data;
            }
            fclose($handle);
        }
        return $headers;
    }
}