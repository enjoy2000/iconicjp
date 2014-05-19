<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');
$installer->updateAttribute('customer', 'birth_year', 'backend_type','text');
 
$installer->run("
 

 
    ");
	

 
$installer->endSetup();