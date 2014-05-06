<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');
$customerEntityTypeId=$installer->getEntityTypeId('customer');
$customerAddressEntityTypeId=$installer->getEntityTypeId('customer_address');

$installer->updateAttribute($customerEntityTypeId, 'firstname', array(
    'is_required' => false,
    'is_system' => false
));
$installer->updateAttribute($customerEntityTypeId, 'lastname', array(
    'is_required' => false,
    'is_system' => false
));
$installer->updateAttribute($customerAddressEntityTypeId, 'firstname', array(
    'is_required' => false,
    'is_system' => false
));
$installer->updateAttribute($customerAddressEntityTypeId, 'lastname', array(
    'is_required' => false,
    'is_system' => false
));

 
$installer->endSetup();