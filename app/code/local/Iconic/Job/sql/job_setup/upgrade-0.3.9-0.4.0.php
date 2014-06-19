<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('job/feature')};
CREATE TABLE {$this->getTable('job/feature')} (
  `feature_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `name_en` varchar(255) NOT NULL default '',
  `url_key` var_char(255) NOT NULL default '',
  PRIMARY KEY (`feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('job')} ADD COLUMN feature_id int(11) unsigned NULL;

INSERT INTO {$this->getTable('job/feature')} (name,name_en,url_key) VALUES ('日本','Japan');
    ");
 
$installer->endSetup();