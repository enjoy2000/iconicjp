<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("

ALTER TABLE  {$this->getTable('job/pic')} ADD COLUMN  `last_pic` varchar(11) NULL;
");
	

 
$installer->endSetup();