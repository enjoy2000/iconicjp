<?php
 
class Iconic_Client_Block_Adminhtml_Company_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('company_form', array('legend'=>Mage::helper('client')->__('Company')));
       
        $fieldset->addField('firstname', 'text', array(
            'label'     => Mage::helper('client')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'firstname',
        ));
		
		$fieldset->addField('company_name', 'text', array(
            'label'     => Mage::helper('client')->__('Company Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'company_name',
        ));
		
		$fieldset->addField('company_website', 'text', array(
            'label'     => Mage::helper('client')->__('Company Website'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'company_website',
        ));
		
		$fieldset->addField('company_detail', 'textarea', array(
            'label'     => Mage::helper('client')->__('Company Detail'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'company_detail',
        ));
 
        if ( Mage::getSingleton('adminhtml/session')->getCompanyData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCompanyData());
            Mage::getSingleton('adminhtml/session')->setCompanyData(null);
        } elseif ( Mage::registry('company_data') ) {
            $form->setValues(Mage::registry('company_data')->getData());
        }
        return parent::_prepareForm();
    }
}