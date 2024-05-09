<?php

$installer = $this;

$installer->startSetup();

// Add unique constraint on the combination of product_id and competitor_id
$installer->getConnection()->addKey(
    $installer->getTable('repricer/matching'),
    'UNQ_PRODUCT_COMPETITOR',
    array('product_id', 'competitor_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();