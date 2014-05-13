<?php
 
class Iconic_Job_Block_Adminhtml_Pic_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'job';
        $this->_controller = 'adminhtml_pic';
 
        $this->_updateButton('save', 'label', Mage::helper('job')->__('Save Pic'));
        $this->_updateButton('delete', 'label', Mage::helper('job')->__('Delete Pic'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('pic_data') && Mage::registry('pic_data')->getId() ) {
            return Mage::helper('job')->__("Edit Pic '%s'", $this->htmlEscape(Mage::registry('pic_data')->getName()));
        } else {
            return Mage::helper('job')->__('Add Pic');
        }
    }
}