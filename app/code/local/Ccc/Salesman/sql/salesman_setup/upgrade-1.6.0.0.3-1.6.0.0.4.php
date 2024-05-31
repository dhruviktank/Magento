<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('salesman_bonus_configuration');

$table = $installer->getConnection()
    ->newTable($tableName)
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity ID')
    ->addColumn('league_number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'League Number')
    ->addColumn('metric', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Metric')
    ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '10,2', array(
        'nullable' => false,
    ), 'Amount')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ), 'Rank')
    ->addColumn('users_in_league', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ), 'Users in League')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '0',
    ), 'Status')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Start Date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'End Date')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_UPDATE,
    ), 'Update Time');

$installer->getConnection()->createTable($table);

$installer->endSetup();