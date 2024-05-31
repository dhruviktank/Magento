<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('salesman_order_commission'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
    ), 'Order ID')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
    ), 'User ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true,
    ), 'Product ID')
    ->addColumn('metric', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Metric')
    ->addColumn('old_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable' => true,
    ), 'Old Price')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Description')
    ->addColumn('new_price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable' => true,
    ), 'New Price')
    ->addColumn('upsold', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable' => true,
    ), 'Upsold')
    ->addColumn('percentage', Varien_Db_Ddl_Table::TYPE_DECIMAL, '5,2', array(
        'nullable' => true,
    ), 'Percentage')
    ->addColumn('commission', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable' => true,
    ), 'Commission')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
    ), 'Updated At')
    ->addColumn('is_paid', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Is Paid')
    ->addColumn('paid_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
    ), 'Paid Date')
    ->addColumn('paid_by', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'Paid By')
    ->setComment('Salesman Order Commission Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();