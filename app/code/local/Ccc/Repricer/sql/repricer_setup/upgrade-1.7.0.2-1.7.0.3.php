<?php

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_repricer_matching');
$connection = $installer->getConnection();

$connection->modifyColumn($tableName, 'reason', array(
    'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable' => false,
    'default'  => 1,
    'comment'  => 'Reason'
));

$installer->endSetup();

