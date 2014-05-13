<?php
 
class Iconic_Job_Block_Adminhtml_Pic extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_pic';
        $this->_blockGroup = 'job';
        $this->_headerText = Mage::helper('job')->__('Pic Manager');
        $this->_addButtonLabel = Mage::helper('job')->__('Add Pic');
        parent::__construct();
    }
}