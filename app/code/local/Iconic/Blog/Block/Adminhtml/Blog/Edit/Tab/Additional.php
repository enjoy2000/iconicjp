<?php
/**
 * ICONIC Co., LTD
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ICONIC License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * 
 *
 * @category   ICONIC
 * @package    Iconic_Blog
 * @copyright  Copyright (c) 2012 ICONIC Co., LTD (http://iconic-jp.com)
 * @license    
 */

class Iconic_Blog_Block_Adminhtml_Blog_Edit_Tab_Additional extends Mage_Adminhtml_Block_Widget_Form
{
    public function initForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('blog_time_data',
            array('legend'=>Mage::helper('blog')->__('Blog Time Settings'), 'style' => 'width: 520px;'));

        $fieldset->addField('blog_time', 'date', array(
            'name' => 'blog_time',
            'label' => Mage::helper('blog')->__('Blog Time'),
            'title' => Mage::helper('blog')->__('Blog Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'after_element_html' =>
                '<span class="hint" style="white-space:nowrap;"><p class="note">'.
                    Mage::helper('blog')->__('Next to the Article will be stated current time').'</p></span>'
        ));

        $fieldset->addField('publicate_from_time', 'date', array(
            'name' => 'publicate_from_time',
            'label' => Mage::helper('blog')->__('Publish From:'),
            'title' => Mage::helper('blog')->__('Publish From:'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));

        $values = $this->getTimeValues(0, 23);
        $fieldset->addField('publicate_from_hours', 'select', array(
            'label'     => Mage::helper('blog')->__('Hours'),
            'name'      => 'publicate_from_hours',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $values = $this->getTimeValues(0, 59);
        $fieldset->addField('publicate_from_minutes', 'select', array(
            'label'     => Mage::helper('blog')->__('Minutes'),
            'name'      => 'publicate_from_minutes',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $fieldset->addField('publicate_to_time', 'date', array(
            'name' => 'publicate_to_time',
            'label' => Mage::helper('blog')->__('Publish Until:'),
            'title' => Mage::helper('blog')->__('Publish Until:'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));

        $values = $this->getTimeValues(0, 23);
        $fieldset->addField('publicate_to_hours', 'select', array(
            'label'     => Mage::helper('blog')->__('Hours'),
            'name'      => 'publicate_to_hours',
            'style'     => 'width: 50px!important;',
            'values'    => $values
        ));

        $values = $this->getTimeValues(0, 59);
        $fieldset->addField('publicate_to_minutes', 'select', array(
            'label'     => Mage::helper('blog')->__('Minutes'),
            'name'      => 'publicate_to_minutes',
            'style'     => 'width: 50px!important',
            'values'    => $values
        ));

        $fieldset = $form->addFieldset('blog_meta_data', array('legend'=>Mage::helper('blog')->__('Meta Data')));

        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('blog')->__('Keywords'),
            'title' => Mage::helper('blog')->__('Meta Keywords'),
            'style' => 'width: 520px;',
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('blog')->__('Description'),
            'title' => Mage::helper('blog')->__('Meta Description'),
            'style' => 'width: 520px;',
        ));

        $fieldset = $form->addFieldset('blog_options_data',
            array('legend'=>Mage::helper('blog')->__('Advanced Post Options')));


        if ( Mage::getSingleton('adminhtml/session')->getBlogData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ( Mage::registry('blog_data') ) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }
        $this->setForm($form);
        return $this;
    }

    public function getTimeValues($start, $end)
    {
        $values = array();
        for($i=$start; $i<=$end; $i++) {
            $values[] = array('label' => (strlen($i)>1)?$i:('0'.$i), 'value' => $i);
        }
        return $values;
    }
}
