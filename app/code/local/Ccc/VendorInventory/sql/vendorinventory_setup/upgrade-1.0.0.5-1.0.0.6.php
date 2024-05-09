<?php

$installer = $this;
$installer->startSetup();

$entityTypeId ="catalog_product";
$attributeCode ="instock_date";
$attributeLabel ="Instock Date";

$data = [
    "type" => 'varchar',
    "input" => 'date',
    "label" => $attributeLabel,
    "source" =>"eav/entity_attribute_source_table",
    "required" =>false,
    "user_define"=>true,
    "unique"=>false,
];
$installer->addAttribute($entityTypeId,$attributeCode,$data);
$installer->endSetup();