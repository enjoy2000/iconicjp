<?php
 
class Iconic_Job_Block_Adminhtml_Location_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('location_form', array('legend'=>Mage::helper('job')->__('Location')));
       
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
		
		$countries = Mage::getModel('job/country')->getCollection();
		foreach($countries as $country){
			$arrayCountries[] = array(
					'label'		=> $country->getName(),
					'value' 	=> $country->getId(),
					);
		}
		
		$fieldset->addField('country_id', 'select', array(
            'label'     => Mage::helper('job')->__('Country'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'country_id',
            'values'	=> $arrayCountries,
        ));
 
        if ( Mage::getSingleton('adminhtml/session')->getLocationData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getLocationData());
            Mage::getSingleton('adminhtml/session')->setLocationData(null);
        } elseif ( Mage::registry('location_data') ) {
            $form->setValues(Mage::registry('location_data')->getData());
        }
        return parent::_prepareForm();
    }
}