<?php
 
class Iconic_Job_Block_Adminhtml_Request_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('request_form', array('legend'=>Mage::helper('job')->__('Request')));
       
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('job')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));
		
		$fieldset->addField('full_name', 'text', array(
            'label'     => Mage::helper('job')->__('Full Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'full_name',
        ));
		
		$fieldset->addField('company_name', 'text', array(
            'label'     => Mage::helper('job')->__('Company Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'company_name',
        ));
		
		$fieldset->addField('work_content', 'text', array(
            'label'     => Mage::helper('job')->__('Business'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'work_content',
        ));
		
		$fieldset->addField('phone', 'text', array(
            'label'     => Mage::helper('job')->__('Phone'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'phone',
        ));
		
		$fieldset->addField('guarantee', 'text', array(
            'label'     => Mage::helper('job')->__('Guarantee'),
            'name'      => 'guarantee',
        ));
		
		$fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('job')->__('Email'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'email',
        ));
		
		$fieldset->addField('address', 'text', array(
            'label'     => Mage::helper('job')->__('Address'),
            'name'      => 'address',
        ));
		
		$fieldset->addField('job_content', 'textarea', array(
            'label'     => Mage::helper('job')->__('Job Content'),
            'name'      => 'job_content',
        ));
 
        if ( Mage::getSingleton('adminhtml/session')->getRequestData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRequestData());
            Mage::getSingleton('adminhtml/session')->setRequestData(null);
        } elseif ( Mage::registry('request_data') ) {
            $form->setValues(Mage::registry('request_data')->getData());
        }
        return parent::_prepareForm();
    }
}