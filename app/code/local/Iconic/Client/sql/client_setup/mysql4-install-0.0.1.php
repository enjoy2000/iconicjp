<?php
 
$installer = $this;
 
$installer->startSetup();
 
$trans = Mage::helper('job'); 

$setup = Mage::getModel('customer/entity_setup');
$installer->addAttribute('customer', 'company_logo', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => $trans->__('Company Logo'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_logo',
));
$installer->addAttribute('customer', 'company_name', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => $trans->__('Company Name'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_name',
));
$installer->addAttribute('customer', 'company_address', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => $trans->__('Company Adress'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_address',
));
$installer->addAttribute('customer', 'company_size', array(
    'type' => 'int',
    'input' => 'text',
    'label' => $trans->__('Company Size'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_size',
));
$installer->addAttribute('customer', 'company_website', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => $trans->__('Company Website'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_website',
));
$installer->addAttribute('customer', 'company_detail', array(
    'type' => 'text',
    'input' => 'textarea',
    'label' => $trans->__('Company Detail'),
    'global' => 1,
    'visible' => 1,
    'required' => 1,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source' => 'job/entity_company_detail',
));

$installer->run("
 
ALTER TABLE {$this->getTable('job')} ADD COLUMN customer_id int(11) unsigned NULL AFTER job_id;
 
");
	

 
$installer->endSetup();