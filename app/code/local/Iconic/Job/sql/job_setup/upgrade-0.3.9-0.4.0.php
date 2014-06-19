<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('job/feature')};
CREATE TABLE {$this->getTable('job/feature')} (
  `feature_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `name_en` varchar(255) NOT NULL default '',
  PRIMARY KEY (`feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('日本','Japan');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('ベトナム','Vietnam');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('インドネシア','Indonesia');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('韓国','Korea');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('中国','China');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('香港','Hong Kong');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('フィリピン','Phillipines');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('ラオス','Laos');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('カンボジア','Cambodia');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('タイ','Thailand');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('マレーシア','Malaysia');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('シンガポール','Singapore');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('ミャンマー','Myanmar');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('バングラデシュ','Bangradesh');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('インド','India');
INSERT INTO {$this->getTable('job/listcountry')} (name,name_en) VALUES ('海外','Abroad');
    ");
 
$installer->endSetup();