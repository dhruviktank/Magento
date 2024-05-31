<?php

$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$entityType = $installer->getEntityTypeId('catalog_product');
$installer->startSetup();

$attributeCode = 'is_low_seller_product';
$attributeLabel = 'Is Low Seller Product';

$data = array(
    'attribute_code' => $attributeCode,
    'type' => 'int',
    'input' => 'select',
    'label' => $attributeLabel,
    'source' => 'eav/entity_attribute_source_table',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => false,
    'configurable' => false,
    'apply_to' => 'simple,configurable',
    'visible_on_front' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'used_for_promo_rules' => false,
    'is_html_allowed_on_front' => true,
    'option' => array(
        'value' => array(
            'option1' => array(0 => 'Yes'),
            'option2' => array(0 => 'No')
        )
    )
);

$installer->addAttribute($entityType, $attributeCode, $data);

$installer->endSetup();