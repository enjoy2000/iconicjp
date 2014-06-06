<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("

ALTER TABLE {$this->getTable('job')} ADD COLUMN location_text varchar(25) NULL;
    ");
 
$installer->endSetup();