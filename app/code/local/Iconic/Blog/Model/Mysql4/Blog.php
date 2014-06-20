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

class Iconic_Blog_Model_Mysql4_Blog extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct(){
        $this->_init('blog/blog', 'blog_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $urlKey = trim($object->getData('url_key'));
        if ($urlKey=='') {
            $urlKey = $object->getData('title');
        }
        $object->setData('url_key', Mage::helper('blog')->formatUrlKey($urlKey));
        return parent::_beforeSave($object);
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('blog_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('blog_store'), $condition);

        if (count($object->getData('stores')) && (!in_array(0, (array)$object->getData('stores')))) {
            foreach ((array)$object->getData('stores') as $store) {
                $data = array();
                $data['blog_id'] = $object->getId();
                $data['store_id'] = $store;
                $this->_getWriteAdapter()->insert($this->getTable('blog_store'), $data);
            }
        } else {
            $data = array();
            $data['blog_id'] = $object->getId();
            $data['store_id'] = '0';
            $this->_getWriteAdapter()->insert($this->getTable('blog_store'), $data);
        }

        $condition = $this->_getWriteAdapter()->quoteInto('blog_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('blog_category'), $condition);

        foreach ((array)$object->getData('categories') as $category) {
            $data = array();
            $data['blog_id'] = $object->getId();
            $data['category_id'] = $category;
            $this->_getWriteAdapter()->insert($this->getTable('blog_category'), $data);
        }

        return parent::_afterSave($object);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('blog_store'))
            ->where('blog_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $stores = array();
            foreach ($data as $row) {
                $stores[] = $row['store_id'];
            }
            $object->setData('store_id', $stores);
        }

        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('blog_category'))
            ->where('blog_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $categories = array();
            foreach ($data as $row) {
                $categories[] = $row['category_id'];
            }
            $object->setData('category_id', $categories);
        }

        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object){
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('blog/blog_category'), 'blog_id='.$object->getId());
        $adapter->delete($this->getTable('blog/comment'), 'blog_id='.$object->getId());
    }
}
