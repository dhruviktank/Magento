<?php

$installer = $this;

$installer->startSetup();
$table = $installer->getConnection()
    ->newTable($installer->getTable('inventory_brand_configuration'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true
    ), 'Configuration Id')
    ->addColumn('brand_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false
    ), 'Brand Id')
    // ->addColumn('configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    //     'nullable' => false
    // ), 'Configuration Reference Id')
    ->setComment('Inventory Brand Configuration Table');

$installer->getConnection()->createTable($table);
$installer->endSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('inventory_column_configuration'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true
    ), 'Column Configuration Id')
    ->addColumn('brand_configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullble' => false
    ), 'Brand Configuration Reference')
    ->addColumn('isb_column', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true
    ), 'ISB Column')
    ->addColumn('brand_column_configuration', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true
    ), 'Brand Column Configuration')
    ->setComment('Inventory Column Configuration');

$installer->getConnection()->createTable($table);
$installer->endSetup();