<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('outlook/configuration'))
    ->addColumn('configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Configuration ID')
    ->addColumn('username', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Username')
    ->addColumn('password', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Password')
    ->addColumn('api_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'API URL')
    ->addColumn('api_key', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'API Key')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 255, array(
        'nullable' => false,
    ), 'Is Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 255, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 255, array(
        'nullable' => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
    ), 'Updated At')
    ->setComment('ccc_outlook_configuration Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();