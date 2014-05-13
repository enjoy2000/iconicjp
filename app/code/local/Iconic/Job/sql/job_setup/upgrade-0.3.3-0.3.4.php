<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("

ALTER TABLE  {$this->getTable('job/pic')} ADD COLUMN  `last_pic` tinyint NOT NULL DEFAULT 0;
ALTER TABLE  {$this->getTable('job/pic')} CHANGE  `last_pic`  `last_pic` BOOLEAN NULL DEFAULT FALSE ;
");
	

 
$installer->endSetup();