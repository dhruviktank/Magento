<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('inventory_column_configuration');

// Check if the column needs to be renamed
if ($installer->getConnection()->tableColumnExists($tableName, 'brand_Column_configuration')) {
    // Run direct SQL query to rename the column
    $installer->run("ALTER TABLE `inventory_column_configuration`
    DROP `isb_column`;");
}

$installer->endSetup();
