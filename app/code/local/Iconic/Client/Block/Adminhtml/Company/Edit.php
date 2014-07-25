<?php
 
class Iconic_Client_Block_Adminhtml_Company_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'client';
        $this->_controller = 'adminhtml_company';
 
        $this->_updateButton('save', 'label', Mage::helper('client')->__('Save Company'));
        $this->_updateButton('delete', 'label', Mage::helper('client')->__('Delete Company'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('company_data') && Mage::registry('company_data')->getId() ) {
            return Mage::helper('client')->__("Edit Company '%s'", $this->htmlEscape(Mage::registry('company_data')->getName()));
        } else {
            return Mage::helper('client')->__('Add Company');
        }
    }
}