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

class Iconic_Blog_Block_Adminhtml_Blog_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	protected function _prepareLayout() {
	    parent::_prepareLayout();
	    if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
	        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
	        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
	    }
	}
	
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'blog';
        $this->_controller = 'adminhtml_blog';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('blog')->__('Save Blog Article'));
        $this->_updateButton('delete', 'label', Mage::helper('blog')->__('Delete Blog Article'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('blog_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'blog_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'blog_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function checkboxSwitch(){
                if (jQuery('#use_full_img').is(':checked')) {
                    jQuery('#image_short_content').parent().parent().css('display','none');
                } else {
                    jQuery('#image_short_content').parent().parent().css('display', 'table-row');
                    jQuery('#image_short_content').siblings('a').css('float', 'left');
                    jQuery('#image_short_content').siblings('a').css('margin-right', '4px');
                    jQuery('#image_short_content').parent().parent().css('width','155px');
                }
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('blog_data') && Mage::registry('blog_data')->getId()) {
            return Mage::helper('blog')->__("Edit Blog Article '%s'",
                $this->htmlEscape(Mage::registry('blog_data')->getTitle()));
        } else {
            return Mage::helper('blog')->__('Add Blog Article');
        }
    }
}
