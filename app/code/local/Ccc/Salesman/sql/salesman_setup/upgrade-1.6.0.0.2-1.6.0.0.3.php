<?php
$installer = $this;
$installer->startSetup();

/**
 * create salesman_rank table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('salesman_rank'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'enttiy ID')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'user ID')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ), 'user Rank')
    ->addColumn('metric_data', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'metric')
    ->addColumn('percentage', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ), 'percentage')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Update Time')
    ->setComment('salesman salesman_rank table');
$installer->getConnection()->createTable($table);