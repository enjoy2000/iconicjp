<?php
 
class Iconic_Job_Block_Adminhtml_Request extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_request';
        $this->_blockGroup = 'job';
        $this->_headerText = Mage::helper('job')->__('Request Manager');
        $this->_addButtonLabel = Mage::helper('job')->__('Add Request');
        parent::__construct();
    }
}