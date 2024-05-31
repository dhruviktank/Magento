<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('filterreport/report'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Report ID')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'User Id')
    ->addColumn('report_type', Varien_Db_Ddl_Table::TYPE_SMALLINT, 255, array(
        'nullable' => false,
    ), 'Report Type')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, 255, array(
    ), 'Is Active')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 255, array(
    ), 'Created At')
    ->addColumn('updated_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, 255, array(
    ), 'Updated Date')
    ->setComment('ccc_filter_report Table');
$installer->getConnection()->createTable($table);

$entityTypeId ="catalog_product";
$attributeCode ="sold_count";
$attributeLabel ="Sold Count";

$data = [
    "type" => 'int',
    "input" => 'text',
    "label" => $attributeLabel,
    "source" =>"eav/entity_attribute_source_table",
    "required" =>false,
    "user_define"=>true,
    "default"=>0,
    "unique"=>false,
];
$installer->addAttribute($entityTypeId,$attributeCode,$data);

$installer->endSetup();