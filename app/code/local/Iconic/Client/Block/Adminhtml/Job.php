<?php
 
class Iconic_Client_Block_Adminhtml_Job extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_job';
        $this->_blockGroup = 'client';
        $this->_headerText = Mage::helper('job')->__('Jobs Manager');
        $this->_addButtonLabel = Mage::helper('job')->__('Add Job');
        parent::__construct();
    }
}