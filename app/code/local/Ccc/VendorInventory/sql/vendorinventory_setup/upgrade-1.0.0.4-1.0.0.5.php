<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('inventory_items'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true
    ), 'Inventory Item Id')
    ->addColumn('brand_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullble' => false
    ), 'Brand Id')
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullble' => false
    ), 'SKU')
    ->addColumn('instock', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true
    ), 'Instock')
    ->addColumn('instock_qty', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true
    ), 'Instock Quantity')
    ->addColumn('restock_qty', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true
    ), 'Restock Quantity')
    ->addColumn('restock_date', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true
    ), 'Restock Date')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true
    ), 'Status')
    ->addColumn('discontinued', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => true
    ), 'Discontinued')
    ->setComment('Inventory Items');

$installer->getConnection()->createTable($table);
$installer->endSetup();