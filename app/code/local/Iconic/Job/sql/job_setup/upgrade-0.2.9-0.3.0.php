<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("

ALTER TABLE  {$this->getTable('job/listlocation')} ADD COLUMN  `name_en` VARCHAR( 255 ) NULL DEFAULT '';
");
	

 
$installer->endSetup();