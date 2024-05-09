<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('inventory_column_configuration');

// Check if the column needs to be dropped
if ($installer->getConnection()->tableColumnExists($tableName, 'isb_column')) {
    // Run direct SQL query to drop the column
    $installer->getConnection()->dropColumn($tableName, 'isb_column');
}

$tableName = $installer->getTable('inventory_brand_configuration');

// Check if the column needs to be added
if (!$installer->getConnection()->tableColumnExists($tableName, 'headers')) {
    // Run direct SQL query to add the column
    $installer->getConnection()->addColumn(
        $tableName,
        'headers',
        array(
            'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
            'nullable' => false,
            'comment'  => 'Headers'
        )
    );
}

$installer->endSetup();
