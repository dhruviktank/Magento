<?php
class Ccc_Repricer_Model_Observer
{
  public function updateMatchingData()
  {
    $CompData = [];
    $competitors = Mage::getModel('repricer/competitor')
      ->getCollection()
      ->getAllIds();
    // Collect data inside the loop
    foreach ($competitors as $_competitors) {
      $collection = Mage::getModel('catalog/product')
      ->getCollection();
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
    // die;
    // Save data outside the loop
    if (!empty($CompData)) {
      $resource = Mage::getSingleton('core/resource');
      $writeConnection = $resource->getConnection('core_write');
      $tableName = $resource->getTableName('repricer/matching');
      $writeConnection->insertMultiple($tableName, $CompData);
    }
  }
  public function downloadCsv()
    {
        $folderPath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'download';
        // Scan the folder for CSV files added in the last 24 hours
        $files = glob($folderPath . DIRECTORY_SEPARATOR . '*_pending.csv');
        
        foreach ($files as $file)
        {
            $row = 0;
            $header = [];
            if (($handle = fopen($file, 'r')) !== FALSE)
            {
                $mainData = [];
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
                {
                    if (!$row)
                    {
                        // First row contains headers
                        foreach ($data as &$item)
                        {
                            $item = str_replace(' ', '_', strtolower($item));
                        }
                        $header = $data;
                        $row++;
                        continue;
                    }
                    // Combine headers with data for current row
                    $rowData = array_combine($header, $data);
                    $competitorName = $rowData['competitor_name'];
                    $competitorId = Mage::getModel('repricer/competitor')->load($competitorName, 'name')->getId();
                    $rowData['competitor_id'] = $competitorId;
                    $mainData[] = $rowData;
                    
                }
                fclose($handle);
                foreach ($mainData as &$row) {
                    unset($row['product_sku']);
                    unset($row['competitor_name']);
                }
                unset($row); 
            }
            //print_r($mainData);die;
            $model = Mage::getSingleton('core/resource')->getConnection('core_write');
            $tableName = Mage::getSingleton('core/resource')->getTableName('repricer/matching');

            $result = $model->insertOnDuplicate($tableName, $mainData);
            if ($result)
            {
                $oldName = $file;
                $newName = str_replace("_pending", "_completed_" . time(), $oldName);
                if (file_exists($oldName))
                {
                    if (rename($oldName, $newName))
                    {
                        echo "File renamed successfully.";
                    }
                    else
                    {
                        echo "Error rename file.";
                    }
                }
                else
                {
                    echo "File not found: " . $oldName;
                }
            }
        }
    }
  public function uploadcsv()
  {
    // Specify columns to export
    $selectedColumns = array(
      'product_id' => 'main_table.product_id',
      'product_sku' => 'sku.sku',
      'competitor_name' => 'rc.name',
      'competitor_url' => 'main_table.competitor_url',
      'competitor_sku' => 'main_table.competitor_sku'
    );

    $columns = array_keys($selectedColumns);

    // Fetch grid data
    $collection = Mage::getModel('repricer/matching')->getCollection();
    $collection->getSelect()
      ->join(
        array('rc' => Mage::getSingleton('core/resource')->getTableName('repricer/competitor')),
        'rc.competitor_id = main_table.competitor_id',
        []
      )
      ->join(
        array('sku' => 'catalog_product_entity'),
        'sku.entity_id = main_table.product_id',
        []
      )
      ->reset(Zend_Db_Select::COLUMNS)
      ->columns($selectedColumns);

    // Group data by competitor
    $competitorData = [];
    foreach ($collection as $item) {
      $competitor = $item->getData('competitor_name');
      if (!isset($competitorData[$competitor])) {
        $competitorData[$competitor] = [];
      }
      // Extract selected columns data
      $rowData = [];
      foreach ($columns as $column) {
        $rowData[] = $item->getData($column);
      }
      $competitorData[$competitor][] = $rowData;
    }

    // Export data to separate CSV files for each competitor
    foreach ($competitorData as $competitor => $data) {
      // Prepare CSV content
      $content = implode(',', $columns) . "\n";
      foreach ($data as $row) {
        $content .= implode(',', $row) . "\n";
      }

      // Generate file name with competitor name and current date in d-m-Y format
      $todayDate = date('d-m-Y');
      $fileName = $competitor . '_upload_' . $todayDate . '.csv';

      // Specify file path
      $filePath = Mage::getBaseDir('var') . DS . 'report' . DS . 'cmonitor' . DS . 'upload' . DS . $fileName;

      // Save CSV file
      file_put_contents($filePath, $content);

      // Update filename column in the ccc_repricer_competitor table
      $competitorModel = Mage::getModel('repricer/competitor')->load($competitor, 'name');
      if ($competitorModel->getId()) {
        $competitorModel->setFilename($fileName)->save();
      }

      Mage::log("Data for competitor '$competitor' exported successfully to: " . $filePath);
    }
  }

}

