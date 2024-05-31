<?php
$installer = $this;
$installer->startSetup();

/**
 * Table salesmen_bonus_league_user
 */
$table1 = $installer->getConnection()
    ->newTable($installer->getTable('salesman_bonus_league_user'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'enttiy ID')
    ->addColumn('configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'nullable' => false,
    ), 'configuration_id')
    ->addColumn('league_number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'league_number')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'configuration_id')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'rank')
    ->addColumn('bonus', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'bonus')
    ->setComment('Salesman Bonus League User Table');
$installer->getConnection()->createTable($table1);

/**
 * create table salesmen_bonus_league
 */

$table2 = $installer->getConnection()
    ->newTable($installer->getTable('salesman_bonus_league'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'enttiy ID')
    ->addColumn('configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'configuration_id')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'user_id')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'rank')
    ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'amount')
    ->addColumn('metric', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Metric')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'description')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'start_date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'end_date')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Update Time')
    ->setComment('salesman bonus Table');
$installer->getConnection()->createTable($table2);