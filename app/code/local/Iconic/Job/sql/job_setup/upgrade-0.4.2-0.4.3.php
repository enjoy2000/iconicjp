<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("

ALTER TABLE {$this->getTable('job')} CHANGE `location_id` `location_id` VARCHAR(100) NOT NULL;

");
 
$installer->endSetup();