<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("


ALTER TABLE {$this->getTable('job/level')} ADD COLUMN name_en varchar(255) NULL;

ALTER TABLE {$this->getTable('job/parentcategory')} ADD COLUMN name_en varchar(255) NULL;

ALTER TABLE {$this->getTable('job/location')} ADD COLUMN name_en varchar(255) NULL;
ALTER TABLE {$this->getTable('job/location')} ADD COLUMN country_id varchar(255) NOT NULL;

ALTER TABLE {$this->getTable('job')} ADD COLUMN language_id varchar(255) NULL;
ALTER TABLE {$this->getTable('job')} ADD COLUMN amount int(11) NULL;

ALTER TABLE {$this->getTable('job/type')} ADD COLUMN name_en varchar(255) NULL;

");
	

 
$installer->endSetup();