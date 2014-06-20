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

class Iconic_Blog_Block_Adminhtml_Blog_Edit_Tab_Info extends Mage_Adminhtml_Block_Widget_Form
{
    public function initForm()
    {
    	$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
		$wysiwygConfig->setData('files_browser_window_url', Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'));
		
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('blog_form', array('legend'=>Mage::helper('blog')->__('Blog information')));

        $fieldset->addField('status', 'select', array(
        'label'     => Mage::helper('blog')->__('Status'),
        'name'      => 'status',
        'values'    => array(
          array(
              'value'     => 1,
              'label'     => Mage::helper('blog')->__('Enabled'),
          ),

          array(
              'value'     => 2,
              'label'     => Mage::helper('blog')->__('Disabled'),
          ),
        ),
        ));

        $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('blog')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
        ));

        $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('blog')->__('URL Key'),
          'title'     => Mage::helper('blog')->__('URL Key'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'url_key',
          'class'     => 'validate-identifier',
          'after_element_html' => '<div class="hint"><p class="note">'.$this->__('e.g. domain.com/blog/url_key').'</p></div>',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('store_id', 'multiselect', array(
                    'name'      => 'stores[]',
                    'label'     => Mage::helper('blog')->__('Store View'),
                    'title'     => Mage::helper('blog')->__('Store View'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }		
		
        $fieldset->addField('author', 'text', array(
            'label'     => Mage::helper('blog')->__('Author name'),
            'name'      => 'author',
            'style' => 'width: 520px;',
            'after_element_html' => '<span class="hint"><p class="note">'.$this->__('Leave blank to disable').'</p></span>',
        ));
		
        $categories = array();
        $collection = Mage::getModel('blog/category')->getCollection()->setOrder('sort_id', 'asc');
        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');
        foreach ($collection as $cat) {
            $categories[] = ( array(
                'label' => str_repeat($nonEscapableNbspChar, $cat->getLevel() * 4).(string)$cat->getTitle(),
                'value' => $cat->getCategoryId()
                ));
        }
            $fieldset->addField('category_id', 'multiselect', array(
                    'name'      => 'categories[]',
                    'label'     => Mage::helper('blog')->__('Category'),
                    'title'     => Mage::helper('blog')->__('Category'),
                    'required'  => false,
                    'style'     => 'height:100px',
                    'values'    => $categories,
            ));

        $blogCollection = Mage::getModel('blog/blog')->getCollection()
                            ->addFieldToFilter('blog_id', $this->getRequest()->getParam('id'));
        
        $data = Mage::registry('blog_data');
        if ($data && (($data->getImageShortContent() == $data->getImageFullContent()) || $data->getImageShortContent() == '' || !$data->getImageShortContent())) {
            $fieldset->addField('use_full_img', 'checkbox', array(
                'label'     => Mage::helper('blog')->__('Use Full Description Image'),
                'required'  => false,
                'name'      => 'use_full_img',
                'onclick'   => 'checkboxSwitch();',
                'checked'   => true,
            ));
            $fieldset->addField('image_short_content', 'image', array(
                'label'     => Mage::helper('blog')->__('Image for Short Description'),
                'required'  => false,
                'name'      => 'image_short_content',
                'after_element_html' => '<script type="text/javascript">jQuery("#image_short_content").parent().parent().css("display","none");</script>',
            ));

        } else {
            $fieldset->addField('use_full_img', 'checkbox', array(
                'label'     => Mage::helper('blog')->__('Use Full Description Image'),
                'required'  => false,
                'name'      => 'use_full_img',
                'onclick'   => 'checkboxSwitch();'
            ));

            $fieldset->addField('image_short_content', 'image', array(
                'label'     => Mage::helper('blog')->__('Image for Short Description'),
                'required'  => true,
                'name'      => 'image_short_content',
            ));
        }

        $fieldset->addField('short_content', 'editor', array(
            'name'      => 'short_content',
            'label'     => Mage::helper('blog')->__('Short Description'),
            'title'     => Mage::helper('blog')->__('Short Description'),
            'config'    => $wysiwygConfig,
            'wysiwyg' => true
        ));

        $fieldset->addField('image_full_content', 'image', array(
            'label'     => Mage::helper('blog')->__('Image for Full Description'),
            'required'  => false,
            'name'      => 'image_full_content',
        ));

        $fieldset->addField('full_content', 'editor', array(
            'name'      => 'full_content',
            'label'     => Mage::helper('blog')->__('Full Description'),
            'title'     => Mage::helper('blog')->__('Full Description'),
            'style'     => 'height:36em',
    		'config'    => $wysiwygConfig,
            'wysiwyg'   => true
        ));


        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ( Mage::registry('blog_data') ) {
            $data = Mage::registry('blog_data');
            if (($data->getImageShortContent() == $data->getImageFullContent()) || $data->getImageShortContent() == '' || !$data->getImageShortContent()) {
                $data->setUseFullImg(1);
            }
            $form->setValues($data->getData());
        }

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'hidden', array(
                            'name'      => 'stores[]',
                            'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        $this->setForm($form);
        return $this;
    }
}
