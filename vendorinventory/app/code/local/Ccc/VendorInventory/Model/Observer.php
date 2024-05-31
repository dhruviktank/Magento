<?php

class Ccc_VendorInventory_Model_Observer
{
    public function readCsv()
    {
        echo "<pre>";
        $brands = Mage::helper('vendorinventory')->getBrandNames();

        foreach ($brands as $brandId => $_brandName) {
            $collection = Mage::getModel('vendorinventory/configuration_column')
                ->getCollection()
                ->addFieldtoFilter('configuration.brand_id', $brandId)
                ->join('configuration', 'main_table.brand_configuration_id=configuration.id');
            $brandConfig = json_decode($collection->getFirstItem()->getBrandColumnConfiguration());

            if (is_null($brandConfig))
                continue;

            $file = fopen(Mage::getBaseDir('var') . DS . 'inventory' . DS . $brandId . DS . 'inventory.csv', 'r');
            $headers = fgetcsv($file);
            while ($row = fgetcsv($file)) {
                $model = Mage::getModel("vendorinventory/items");
                $data = array_combine($headers, $row);
                $temp = [];
                $temp['brand_id'] = $brandId;
                foreach ($brandConfig as $_column => $_config) {
                    $dataColumn = '';
                    $temp[$_column] = null;
                    $rule = [];
                    foreach ($_config as $_c) {
                        if (!is_string($_c)) {
                            foreach ($_c as $_k => $_v) {
                                $dataColumn = $_k;
                                if ($_column == 'sku') {
                                    $itemCollection = $model->getCollection()->addFieldtoFilter('sku', $data[$_k]);
                                    if ($itemCollection->getFirstItem()->getId()) {
                                        $model->load($itemCollection->getFirstItem()->getId());
                                    }
                                    $rule[] = true;
                                    break;
                                }
                                // echo $_column; die;
                                if($_column == 'restock_date'){
                                    $rule[] = true;
                                }
                                if ($_v->condition_value != '') {
                                    $rule[] = $this->checkRule(
                                        $data[$_k],
                                        $_v->condition_value,
                                        $_v->data_type,
                                        $_v->condition_operator
                                    );
                                } else {
                                    $rule[] = false;
                                }
                            }
                        } else {
                            switch ($_c) {
                                case "AND":
                                    $rule[] = "AND";
                                    break;
                                case "OR":
                                    $rule[] = "OR";
                                    break;
                            }
                        }
                    }
                    $result = false;
                    $logicalOperator = '';
                    foreach ($rule as $item) {
                        if ($item === "AND" || $item === "OR") {
                            $logicalOperator = $item;
                        } else {
                            if ($logicalOperator === "AND") {
                                $result = $result && $item;
                            } else {
                                $result = $result || $item;
                            }
                        }
                    }
                    $value = 0;
                    if ($result) {
                        switch ($_column) {
                            case "sku":
                            case "restock_date":
                                $value = $data[$dataColumn];
                                break;
                            case "instock":
                            case "instock_qty":
                            case "restock_qty":
                            case "status":
                            case "discontinued":
                                $value = 1;
                                break;
                        }
                    }
                    $temp[$_column] = $value;
                }
                $model->addData($temp)->save();
                print_r($temp);
            }
        }
    }
    public function checkRule($dataValue, $condValue, $condDataType, $condOperator)
    {
        switch (strtolower($condDataType)) {
            case "count":
            case "number":
                return $this->compareValues((int) $dataValue, (int) $condValue, $condOperator);
            case "text":
                return $this->compareValues(strtolower($dataValue), strtolower($condValue), $condOperator);
            case "date":
                $date1 = DateTime::createFromFormat('m/d/Y', $dataValue);
                $date2 = DateTime::createFromFormat('Y-m-d', $condValue);
                return $this->compareValues($date1, $date2, $condOperator);
        }
    }
    public function compareValues($value1, $value2, $operator)
    {
        switch ($operator) {
            case "=":
                return $value1 == $value2;
            case "!=":
                return $value1 != $value2;
            case ">":
                return $value1 > $value2;
            case ">=":
                return $value1 >= $value2;
            case "<":
                return $value1 < $value2;
            case "<=":
                return $value1 <= $value2;
        }
    }

    public function applyRule()
    {
        // print_r(Mage::getStoreConfig('banner/general/banner_enabled'));
        $collection = Mage::getModel('vendorinventory/items')->getCollection()->getItems();
        foreach ($collection as $item) {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getSku());
            if ($item->getInstock() == 1) {
                $product->setData('instock_date', date('d/m/Y'))->getResource()->saveAttribute($product, 'instock_date');
                // $product->addAttributeUpdate('instock_date', date('d/m/Y'), 1);
            } else if ($item->getRestockDate() != 0) {
                $restockDate = DateTime::createFromFormat('m/d/Y', $item->getRestockDate())->format('d/m/Y');
                $product->setData('instock_date', $restockDate)->getResource()->saveAttribute($product, 'instock_date');
                // $product->addAttributeUpdate('instock_date', $restockDate, 1);
            } else {
                $product->setData('instock_date', null)->getResource()->saveAttribute($product, 'instock_date');
            }
        }
    }

    public function customEventMethod(Varien_Event_Observer $observer){
        print_r($observer->getEvent()->getBanner()->getData());
    }
}