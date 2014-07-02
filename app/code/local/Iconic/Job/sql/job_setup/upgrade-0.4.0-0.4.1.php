<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('job/langlevel')};
CREATE TABLE {$this->getTable('job/langlevel')} (
  `langlevel_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `name_en` varchar(255) NOT NULL default '',
  PRIMARY KEY (`langlevel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('ネイティブレベル','Native');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('ビジネス上級レベル','Advanced');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('ビジネス中級レベル','Upper-Intermediate');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('日常会話レベル','Intermediate');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('旅行会話レベル','Pre-Intermediate');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('挨拶レベル','Elementary');
INSERT INTO {$this->getTable('job/langlevel')} (name,name_en) VALUES ('話せない','Not Proficient');

");
 
$installer->endSetup();