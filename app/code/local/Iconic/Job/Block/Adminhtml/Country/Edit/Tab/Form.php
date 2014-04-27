<?php
 
class Iconic_Job_Block_Adminhtml_Country_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('country_form', array('legend'=>Mage::helper('job')->__('Country')));
       
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('job')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));
		
		$fieldset->addField('name_en', 'text', array(
            'label'     => Mage::helper('job')->__('Name En'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name_en',
        ));
 
        if ( Mage::getSingleton('adminhtml/session')->getCountryData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCountryData());
            Mage::getSingleton('adminhtml/session')->setCountryData(null);
        } elseif ( Mage::registry('country_data') ) {
            $form->setValues(Mage::registry('country_data')->getData());
        }
        return parent::_prepareForm();
    }
}