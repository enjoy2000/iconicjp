<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("

ALTER TABLE  {$this->getTable('job/country')} CHANGE  `name_jp`  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE  {$this->getTable('job/language')} CHANGE  `name_jp`  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE  {$this->getTable('job/language')} ADD COLUMN  `flag` VARCHAR( 255 ) NULL DEFAULT '';
");
	

 
$installer->endSetup();