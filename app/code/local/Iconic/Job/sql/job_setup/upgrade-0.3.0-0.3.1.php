<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('job/request')};
CREATE TABLE {$this->getTable('job/request')} (
  `request_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `full_name` varchar(255) NOT NULL default '',
  `company_name` varchar(255) NOT NULL default '',
  `work_content` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `guarantee` varchar(255) NULL default '',
  `email` varchar(255) NOT NULL default '',
  `address` varchar(255) NULL default '',
  `job_content` text NULL default '',
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
    ");
 
$installer->endSetup();