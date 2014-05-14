<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');
$installer->addAttribute('customer', 'phone', array(
    'type' => 'int',
    'input' => 'text',
    'label' => $trans->__('Phone'),
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_phone',
));
 
$installer->run("
 

 
    ");
	

 
$installer->endSetup();