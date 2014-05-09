<?php
 
class Iconic_Job_Block_Adminhtml_Request_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'job';
        $this->_controller = 'adminhtml_request';
 
        $this->_updateButton('save', 'label', Mage::helper('job')->__('Save Request'));
        $this->_updateButton('delete', 'label', Mage::helper('job')->__('Delete Request'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('request_data') && Mage::registry('request_data')->getId() ) {
            return Mage::helper('job')->__("Edit Request '%s'", $this->htmlEscape(Mage::registry('request_data')->getName()));
        } else {
            return Mage::helper('job')->__('Add Request');
        }
    }
}