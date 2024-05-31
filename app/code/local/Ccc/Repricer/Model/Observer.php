<?php

class Ccc_Repricer_Model_Observer
{
    public function saveNewData()
    {
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $tableName = $resource->getTableName('repricer/matching');
        $CompData = [];
        $competitors = Mage::getModel('repricer/competitor')
            ->getCollection()
            ->getAllIds();
        // Collect data inside the loop
        foreach ($competitors as $_competitors) {
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->getSelect()
                ->columns('e.entity_id')
                ->joinLeft(
                    ['CRM' => Mage::getSingleton('core/resource')->getTableName('repricer/matching')],
                    "e.entity_id = CRM.product_id AND CRM.competitor_id = {$_competitors}",
                    ['competitor_id']
                )
                ->where('CRM.competitor_id IS NULL');

            foreach ($collection as $_collection) {
                $CompData[] = [
                    'competitor_id' => $_competitors,
                    'product_id' => $_collection->getEntityID(),
                ];
            }
        }
        if (!empty($CompData)) {
            $writeConnection->insertMultiple($tableName, $CompData);
        }
    }
    public function exportCsv()
    {
        $columns = [
            'product_id' => 'product_id',
            'product_sku' => 'CPE.sku',
            'competitor_name' => 'CRC.name',
            'competitor_url' => 'competitor_url',
            'competitor_sku' => 'competitor_sku',
        ];

        $collection = Mage::getModel('repricer/matching')->getCollection()
            ->join(
                array('CRC' => 'repricer/competitor'),
                'main_table.competitor_id = CRC.competitor_id',
                array()
            )
            ->join(
                array('CPE' => 'catalog/product'),
                'main_table.product_id = CPE.entity_id',
                array()
            );

        $collection->getSelect()->order('repricer_id ASC')->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);

        $dataArray = $collection->getData();

        $competitorData = [];
        foreach ($dataArray as $data) {
            $competitorName = $data['competitor_name'];
            $competitorData[$competitorName][] = $data;
        }

        $filePaths = [];
        foreach ($competitorData as $competitorName => $data) {
            $csv = '';
            $headerRow = array_keys($data[0]);
            $csv .= implode(',', $headerRow) . "\n";
            foreach ($data as $index => $row) {
                $csvRow = array_map(function ($value) {
                    return '"' . str_replace('"', '""', $value) . '"';
                }, $row);
                $csv .= implode(',', $csvRow) . "\n";
            }
            $csv = rtrim($csv, "\n");
            $filePath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload' . DS . $competitorName . '_upload_' . time() . '.csv';
            file_put_contents($filePath, $csv);
            $filePaths[] = $filePath;
        }
        return $filePaths;
    }
    public function importCsv()
    {
        $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';
        $files = glob($folderPath . DS . '*_pending.csv');

        $model = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tableName = Mage::getSingleton('core/resource')->getTableName('repricer/matching');

        foreach ($files as $file) {
            if (($handle = fopen($file, 'r')) !== FALSE) {
                $parsedData = [];
                $header = [];

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (empty($header)) {
                        $header = array_map(function ($item) {
                            return str_replace(' ', '_', strtolower($item));
                        }, $data);
                        continue;
                    }

                    $row = array_combine($header, $data);
                    $competitorName = $row['competitor_name'];
                    $competitorId = Mage::getModel('repricer/competitor')->load($competitorName, 'name')->getId();
                    $row['competitor_id'] = $competitorId;

                    unset($row['product_sku'], $row['competitor_name']);
                    $parsedData[] = $row;
                }
                fclose($handle);

                $model->insertOnDuplicate($tableName, $parsedData);

                $filename = basename($file);
                $fileDirectory = dirname($file);
                $newFilePath = $fileDirectory . DS . str_replace('_pending', '_completed', $filename);
                echo 'Repricer updated successfully';
                rename($file, $newFilePath);
            }
        }
    }
    public function oldFunction()
    {
        // const TIME_SPAN = 24 * 3600;
        // public function addNewCompetitorToMatching()
        // {
        //     $timestamp = time();
        //     $currentTimeStamp = date('Y-m-d H:i:s', $timestamp);
        //     $data = [];

        //     $competitorCollection = (Mage::getModel('repricer/competitor')->getCollection());
        //     $productCollection = Mage::getModel('catalog/product')
        //         ->getCollection()
        //         ->addAttributeToSelect(['name', 'status'])
        //         ->addAttributeToFilter('status', 1);

        //     foreach ($competitorCollection as $_comp) {
        //         $difference = strtotime($currentTimeStamp) - strtotime($_comp->getCreatedDate());
        //         if ($difference <= self::TIME_SPAN) {
        //             foreach ($productCollection as $_product) {
        //                 // Initialize $data for each product
        //                 $data[] = [
        //                     'competitor_id' => $_comp->getCompetitorId(),
        //                     'product_id' => $_product->getEntityId(),
        //                 ];
        //             }
        //         }
        //     }
        //     if (!empty($data)) {
        //         $matchingModel = Mage::getModel('repricer/matching');
        //         foreach ($data as $_data) {
        //             $matchingModel->setData($_data)->save();
        //         }
        //     } else {
        //         echo "No data to save.";
        //     }
        // }
        // public function addNewProductToMatching()
        // {
        //     $matchingModel = Mage::getModel('repricer/matching');
        //     $noOfRowInMatching = sizeof($matchingModel->getCollection()->addFieldToSelect('repricer_id')->getData());

        //     $timestamp = time();
        //     $currentTimeStamp = date('Y-m-d H:i:s', $timestamp);
        //     $data = [];
        //     $productCollection = (Mage::getModel('catalog/product')->getCollection())
        //         ->addAttributeToSelect(['name', 'status'])
        //         ->addAttributeToFilter('status', 1);
        //     $competitorCollection = Mage::getModel('repricer/competitor')->getCollection();
        //     foreach ($productCollection as $_product) {
        //         $difference = strtotime($currentTimeStamp) - strtotime($_product->getCreatedAt());
        //         if ($difference <= Ccc_Repricer_Model_Observer::TIME_SPAN || $noOfRowInMatching <= 0) {
        //             foreach ($competitorCollection as $_competitor) {
        //                 $timediff = strtotime($currentTimeStamp) - strtotime($_competitor->getCreatedDate());
        //                 if ($timediff >= Ccc_Repricer_Model_Observer::TIME_SPAN || $noOfRowInMatching <= 0) {
        //                     $data[] = [
        //                         'competitor_id' => $_competitor->getCompetitorId(),
        //                         'product_id' => $_product->getId(),
        //                         'competitor_url' => $_competitor->getUrl(),
        //                     ];
        //                 }
        //             }
        //         }
        //     }
        //     if (!empty($data)) {
        //         foreach ($data as $_data) {
        //             $matchingModel->setData($_data)->save();
        //         }
        //     } else {
        //         echo "No data to save.";
        //     }
        // }
        // public function importCsv()
        // {
        //     $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';
        //     // Get the current date in d-m-Y format
        //     $currentDate = date('d-m-Y');
        //     $files = glob($folderPath . DIRECTORY_SEPARATOR . '*_pending.csv');
        //     $totalUpdates = 0;
        //     $totalSaves = 0;

        //     foreach ($files as $file) {
        //         $fileModificationTime = filemtime($file);
        //         $timeDifference = strtotime($currentDate) - $fileModificationTime;
        //         if ($timeDifference <= 86400) {
        //             $parsedData = $this->_parseCsv($file);
        //             foreach ($parsedData as $row) {
        //                 $productId = $row['product_id'];
        //                 $competitorUrl = $row['Competitor Url'];
        //                 $competitorSku = $row['Competitor Sku'];

        //                 $matchingModel = Mage::getModel('repricer/matching')
        //                     ->getCollection()
        //                     ->addFieldToFilter('product_id', $productId)
        //                     ->addFieldToFilter('competitor_url', $competitorUrl)
        //                     ->addFieldToFilter('competitor_sku', $competitorSku)
        //                     ->getFirstItem();

        //                 if ($matchingModel->getId()) {
        //                     $matchingModel->setCompetitorPrice($row['Competitor Price']);
        //                     $matchingModel->setReason($row['Reason']);
        //                     $matchingModel->save();

        //                     $totalUpdates++;
        //                     $totalSaves++;
        //                 } else {
        //                     $totalUpdates++;
        //                 }
        //             }

        //             $newFilename = str_replace('_pending.csv', '_completed_' . $currentDate . '.csv', $file);
        //             rename($file, $newFilename);
        //         }
        //     }
        //     $totalError = $totalUpdates - $totalSaves;

        //     echo "Total erros  processed: $totalError\n";

        //     echo "Total updates done: $totalUpdates\n";

        //     echo "Total saves done: $totalSaves\n";
        // }
        // protected function _parseCsv($csvFile)
        // {
        //     $parsedData = [];
        //     $header = [];

        //     if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        //         while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        //             if (!$header) {
        //                 $header = array_map('trim', $data);
        //                 continue;
        //             }
        //             $parsedData[] = array_combine($header, $data);
        //         }
        //         fclose($handle);
        //     }
        //     return $parsedData;
        // }
        // public function exportCsv()
        // {
        //     // Specify columns to export
        //     $selectedColumns = array(
        //         'product_id' => 'main_table.product_id',
        //         'product_sku' => 'sku.sku',
        //         'competitor_name' => 'rc.name',
        //         'competitor_url' => 'main_table.competitor_url',
        //         'competitor_sku' => 'main_table.competitor_sku'
        //     );

        //     $columns = array_keys($selectedColumns);

        //     // Fetch grid data
        //     $collection = Mage::getModel('repricer/matching')->getCollection();
        //     $collection->getSelect()
        //         ->join(
        //             array('rc' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
        //             'rc.competitor_id = main_table.competitor_id',
        //             []
        //         )
        //         ->join(
        //             array('sku' => 'catalog_product_entity'),
        //             'sku.entity_id = main_table.product_id',
        //             []
        //         )
        //         ->reset(Zend_Db_Select::COLUMNS)
        //         ->columns($selectedColumns);

        //     // Group data by competitor
        //     $competitorData = [];
        //     foreach ($collection as $item) {
        //         $competitor = $item->getData('competitor_name');
        //         if (!isset($competitorData[$competitor])) {
        //             $competitorData[$competitor] = [];
        //         }
        //         // Extract selected columns data
        //         $rowData = [];
        //         foreach ($columns as $column) {
        //             $rowData[] = $item->getData($column);
        //         }
        //         $competitorData[$competitor][] = $rowData;
        //     }

        //     // Export data to separate CSV files for each competitor
        //     foreach ($competitorData as $competitor => $data) {
        //         // Prepare CSV content
        //         $content = implode(',', $columns) . "\n";
        //         foreach ($data as $row) {
        //             $content .= implode(',', $row) . "\n";
        //         }

        //         // Generate file name with competitor name and current date in d-m-Y format
        //         $todayDate = date('d-m-Y');
        //         $fileName = $competitor . '_upload_' . $todayDate . '.csv';

        //         // Specify file path
        //         $filePath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload' . DS . $fileName;

        //         // Save CSV file
        //         file_put_contents($filePath, $content);

        //         // Update filename column in the ccc_repricer_competitor table
        //         $competitorModel = Mage::getModel('repricer/competitor')->load($competitor, 'name');
        //         if ($competitorModel->getId()) {
        //             $competitorModel->setFilename($fileName)->save();
        //         }

        //         Mage::log("Data for competitor '$competitor' exported successfully to: " . $filePath);
        //     }
        // }
    }
}