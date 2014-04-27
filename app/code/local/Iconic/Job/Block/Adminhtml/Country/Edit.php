<?php
 
class Iconic_Job_Block_Adminhtml_Country_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'job';
        $this->_controller = 'adminhtml_country';
 
        $this->_updateButton('save', 'label', Mage::helper('job')->__('Save Country'));
        $this->_updateButton('delete', 'label', Mage::helper('job')->__('Delete Country'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('country_data') && Mage::registry('country_data')->getId() ) {
            return Mage::helper('job')->__("Edit Country '%s'", $this->htmlEscape(Mage::registry('country_data')->getName()));
        } else {
            return Mage::helper('job')->__('Add Country');
        }
    }
}