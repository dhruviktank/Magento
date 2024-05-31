<?php

$installer = $this;
$installer->startSetup();

$table1 = $installer->getConnection()
    ->newTable($installer->getTable('salesman_bonus'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'entity ID')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'user ID')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'rank')
    ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'amount')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'description')
    ->addColumn('is_paid', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
        'nullable' => false,
    ), 'is paid')
    ->addColumn('paid_by', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'paid by')
    ->addColumn('start_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'start date')
    ->addColumn('end_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'end date')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => false,
    ), 'Update Time')
    ->setComment('salesman bonus Table');

$installer->getConnection()->createTable($table1);

$installer->getConnection()
    ->addColumn($installer->getTable('salesman_bonus_league'), 'league_number', array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable' => true,
        'comment' => 'League Number'
    )
    );

$installer->endSetup();