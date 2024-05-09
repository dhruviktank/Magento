<?php

$installer = $this;

$installer->startSetup();

/**
 * Create Table 'Ccc/VendorInventory/inventory_configuration'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('inventory_configuration'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Configuration ID')
    ->addColumn('configuration_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'Configuration Name')
    ->addColumn('file_format', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'File Format')
    ->addColumn('file_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'File Names')
    ->setComment('Inventory Configuration Table');
$installer->getConnection()->createTable($table);
$installer->endSetup();

