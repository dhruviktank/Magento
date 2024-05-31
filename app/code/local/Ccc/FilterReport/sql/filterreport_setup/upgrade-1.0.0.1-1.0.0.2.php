<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ccc_filter_report');

$installer->getConnection()->addColumn(
    $tableName,
    'filter_data',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Filter Data'
    )
);

$installer->endSetup();
