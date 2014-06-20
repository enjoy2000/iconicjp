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

class Iconic_Blog_Block_Adminhtml_Blog_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('blogGrid');
        $this->setDefaultSort('blog_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('blog/blog')->getCollection();
        foreach($collection as $item) {
            $stores = $this->lookupStoreIds($item->getId());
            $item->setData('store_id', $stores);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('blog_id', array(
            'header'    => Mage::helper('blog')->__('ID'),
            'align'     =>'right',
            'width'     => '50',
            'index'     => 'blog_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('blog')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('blog')->__('URL Key'),
            'align'     => 'left',
            'index'     => 'url_key',
        ));

        $this->addColumn('author', array(
            'header'    => Mage::helper('blog')->__('Author'),
            'index'     => 'author',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('blog')->__('Created'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'created_time',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('blog')->__('Updated'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'update_time',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('blog')->__('Status'),
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('blog')->__('Enabled'),
                2 => Mage::helper('blog')->__('Disabled')
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('blog')->__('Action'),
                'width'     => '60',
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

        $this->addColumn('view_comments',
            array(
                'header'    =>  Mage::helper('blog')->__('Comments'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('blog')->__('View comments'),
                        'url'       => array('base'=> 'blog/adminhtml_comment/index'),
                        'field'     => 'blog_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('blog_id');
        $this->getMassactionBlock()->setFormFieldName('blog');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('blog')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('blog')->__('Are you sure?')
        ));

        $statuses = array(
              1 => Mage::helper('blog')->__('Enabled'),
              0 => Mage::helper('blog')->__('Disabled')
        );
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('blog')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('blog')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function lookupStoreIds($objectId)
    {
        $adapter = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tableName = Mage::getSingleton('core/resource')->getTableName('blog_blog_store');
        $select  = $adapter->select()
            ->from($tableName, 'store_id')
            ->where('blog_id = ?',(int)$objectId);

        return $adapter->fetchCol($select);
    }
}
