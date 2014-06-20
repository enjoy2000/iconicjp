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

class Iconic_Blog_Block_Adminhtml_Blog_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('blog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('blog')->__('Main Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('info', array(
            'label'     => Mage::helper('blog')->__('Main Information'),
            'content'   => $this->getLayout()->createBlock('blog/adminhtml_blog_edit_tab_info')->initForm()->toHtml(),
        ));

        $this->addTab('additional', array(
            'label'     => Mage::helper('blog')->__('Additional Options'),
            'content'   => $this->getLayout()
                ->createBlock('blog/adminhtml_blog_edit_tab_additional')->initForm()->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
