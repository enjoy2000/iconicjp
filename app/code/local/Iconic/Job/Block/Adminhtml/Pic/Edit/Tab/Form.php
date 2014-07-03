<?php
 
class Iconic_Job_Block_Adminhtml_Pic_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('pic_form', array('legend'=>Mage::helper('job')->__('Pic')));
       
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('job')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));
		
        $fieldset->addField('location', 'select', array(
            'label'     => Mage::helper('job')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'location',
            'values'	=> array(
						array(
							'label' => 'Vietnam',
							'value' => '1'
						),
						array(
							'label' => 'Indonesia',
							'value' => '2'
						),
						array(
							'label' => 'Others',
							'value' => '3'
						),
			)
        ));
		
		$fieldset->addField('interval', 'text', array(
            'label'     => Mage::helper('job')->__('Interval'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'interval',
        ));
 
        if ( Mage::getSingleton('adminhtml/session')->getPicData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPicData());
            Mage::getSingleton('adminhtml/session')->setPicData(null);
        } elseif ( Mage::registry('pic_data') ) {
            $form->setValues(Mage::registry('pic_data')->getData());
        }
        return parent::_prepareForm();
    }
}