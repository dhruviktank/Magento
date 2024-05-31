<?php
$installer = $this;

$installer->startSetup();

// try {
// $addressHelper = Mage::helper('customer/address');
$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// $eavConfig = Mage::getSingleton('eav/config');
// $attributeData = array(
//     'type'            => 'int', // Correct key for backend_type
//     'input'           => 'select', // Correct key for frontend_input
//     'label'           => 'Premium', // Simplified label key
//     'source'          => 'eav/entity_attribute_source_table', // Ensure that the source model exists and is correctly specified
//     'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
//     'is_user_defined'   => 0,
//     'is_system'         => 1,
//     'is_visible'        => 1,
//     'sort_order'        => 20,
//     'is_required'       => 0,
//     'adminhtml_only'    => 1,
// );

// // Check if the attribute 'subscription' exists
// $installer->addAttribute('customer', 'premium', $attributeData);
$attribute = $eavConfig->getAttribute('customer', 'premium');
// print_r($attribute); die;
// if ($attribute && $attribute->getId()) {
    // $attribute->setWebsite($store->getWebsite());
    // $attribute->addData($attributeData);

    $usedInForms = array(
        'customer_account_create',
        'customer_account_edit',
        'checkout_register',
        'adminhtml_customer'
    );
    $attribute->setData('used_in_forms', $usedInForms);

    $attribute->save();
// } else {
//     Mage::log('Attribute "subscription" does not exist.', null, 'upgrade-errors.log');
// }
// } catch (Exception $e) {
//     Mage::logException($e);
// }

$installer->endSetup();

// $installer = $this;

// $installer->startSetup();

// $addressHelper = Mage::helper('customer/address');
// $store         = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// $eavConfig = Mage::getSingleton('eav/config');
// $attributeData = array(
//     'is_user_defined'   => 0,
//     'is_system'         => 1,
//     'is_visible'        => 1,
//     'sort_order'        => 20,
//     'is_required'       => 0,
//     'adminhtml_only'    => 1
// );

// $attribute = $eavConfig->getAttribute('customer', 'subscription');


// $attribute->setWebsite($store->getWebsite());
// $attribute->addData($attributeData);
// $usedInForms = array('adminhtml_customer');
// $attribute->setData('used_in_forms', $usedInForms);

// $attribute->save();

// $installer->endSetup();