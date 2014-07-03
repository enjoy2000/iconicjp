<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
ALTER TABLE {$this->getTable('job/pic')} ADD COLUMN location int(11) unsigned NOT NULL;
ALTER TABLE {$this->getTable('job/pic')} ADD COLUMN last_pic_vn varchar(11) NULL;
ALTER TABLE {$this->getTable('job/pic')} ADD COLUMN last_pic_id varchar(11) NULL;

");
 
$installer->endSetup();