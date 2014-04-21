<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');

 
$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('job/country')};
CREATE TABLE {$this->getTable('job/country')} (
  `country_id` int(11) unsigned NOT NULL auto_increment,
  `name_jp` varchar(255) NOT NULL default '',
  `name_en` varchar(255) NULL default '',
  `url_key` varchar(255) NULL default '',
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('job/language')};
CREATE TABLE {$this->getTable('job/language')} (
  `language_id` int(11) unsigned NOT NULL auto_increment,
  `name_jp` varchar(255) NOT NULL default '',
  `name_en` varchar(255) NULL default '',
  `url_key` varchar(255) NULL default '',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
	

 
$installer->endSetup();