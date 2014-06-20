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

class Iconic_Blog_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('commentGrid');
        $this->setDefaultSort('comment_status');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('blog/comment_collection');
        if ($this->getRequest()->getParam('blog_id')) {
            $collection->addBlogFilter($this->getRequest()->getParam('blog_id'));
        } else {
            $tableName = Mage::getSingleton('core/resource')->getTableName('blog_blog');
            $collection->getSelect()->joinLeft($tableName, 'main_table.blog_id = ' . $tableName . '.blog_id', array($tableName . '.title as title'));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('comment', array(
            'header'    => Mage::helper('blog')->__('Comment'),
            'align'     =>'left',
            'index'     => 'comment',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('blog')->__('Blog Name'),
            'index'     => 'title',
        ));

        $this->addColumn('user', array(
            'header'    => Mage::helper('blog')->__('User'),
            'index'     => 'user',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('blog')->__('E-mail'),
            'index'     => 'email',
        ));

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('blog')->__('Created'),
            'align'     => 'center',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_time',
        ));

        $this->addColumn('comment_status', array(
            'header'    => Mage::helper('blog')->__('Status'),
            'align'     => 'center',
            'width'     => '80px',
            'index'     => 'comment_status',
            'type'      => 'options',
            'options'   => array(
                Iconic_Blog_Helper_Data::UNAPPROVED_STATUS => 'Unapproved',
                Iconic_Blog_Helper_Data::APPROVED_STATUS => 'Approved',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('blog')->__('Action'),
                'width'     => '50',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('blog')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                 ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        /*
        $this->addColumn('view_blog_item',
            array(
                'header'    =>  Mage::helper('blog')->__('Blog Article'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('blog')->__('Go to Blog Article'),
                        'url'       => array('base'=> '* /adminhtml_blog/edit'),
                        'field'     => 'blog_id'
                    ),
                 ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));*/
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('comments');

        $this->getMassactionBlock()->addItem('approve', array(
             'label'    => Mage::helper('blog')->__('Approve'),
             'url'      => $this->getUrl('*/*/massApprove'),
             'confirm'  => Mage::helper('blog')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('unapprove', array(
             'label'    => Mage::helper('blog')->__('Not Approve'),
             'url'      => $this->getUrl('*/*/massUnapprove'),
             'confirm'  => Mage::helper('blog')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('blog')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('blog')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
