<?php
 
class Iconic_Job_Block_Adminhtml_Country extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_country';
        $this->_blockGroup = 'job';
        $this->_headerText = Mage::helper('job')->__('Country Manager');
        $this->_addButtonLabel = Mage::helper('job')->__('Add Country');
        parent::__construct();
    }
}