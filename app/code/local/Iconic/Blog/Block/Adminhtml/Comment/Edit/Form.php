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

class Iconic_Blog_Block_Adminhtml_Comment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $collection = Mage::getResourceModel('blog/comment_collection');

        $blog_id = $this->getRequest()->getParam('id');
        $tableName = Mage::getSingleton('core/resource')->getTableName('blog_blog');
        $collection->getSelect()->joinLeft($tableName, 'main_table.blog_id = '. $tableName . '.blog_id', 'title');
        $collection->getSelect()->distinct();
        $collection->getSelect()->where('main_table.blog_id =' . $blog_id);
        $collection->getSelect()->limit(1);
        $data = $collection->getData();

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $fieldset = $form->addFieldset('comment_form',
            array('legend'=>Mage::helper('blog')->__('Comment Information')));


         $fieldset->addField('title', 'hidden', array(
            'label'     => Mage::helper('blog')->__('Blog Name'),
            'after_element_html' => '<tr><td class="label"><label for="title">Blog Name</label></td>
                <td class="value">' .$data[0]['title'] . '</td></tr>',
        ));

        $fieldset->addField('user', 'text', array(
            'label'     => Mage::helper('blog')->__('User'),
            'name'      => 'user',
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('blog')->__('Email Address'),
            'name'      => 'email',
        ));

        $fieldset->addField('comment_status', 'select', array(
            'label'     => Mage::helper('blog')->__('Status'),
            'name'      => 'comment_status',
            'values'    => array(
                array(
                    'value'     => Iconic_Blog_Helper_Data::UNAPPROVED_STATUS,
                    'label'     => Mage::helper('blog')->__('Unapproved'),
                ),

                array(
                    'value'     => Iconic_Blog_Helper_Data::APPROVED_STATUS,
                    'label'     => Mage::helper('blog')->__('Approved'),
                ),
            ),
        ));

        $fieldset->addField('comment', 'editor', array(
            'name'      => 'comment',
            'label'     => Mage::helper('blog')->__('Comment'),
            'title'     => Mage::helper('blog')->__('Comment'),
            'style'     => 'width:500px; height:250px;',
            'wysiwyg'   => false,
            'required'  => false,
        ));

        if ( Mage::getSingleton('adminhtml/session')->getBlogData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ( Mage::registry('blog_data') ) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
