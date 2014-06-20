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

class Iconic_Blog_Model_Mysql4_Blog_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/blog');
    }

    public function addEnableFilter($status)
    {
        $this->getSelect()
            ->where('status = ?', $status);
        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $this->getSelect()->join(
            array('blog_category_table' => $this->getTable('blog_category')),
            'main_table.blog_id = blog_category_table.blog_id',
            array()
        )->join(
            array('category_table' => $this->getTable('category')),
            'blog_category_table.category_id = category_table.category_id',
            array()
        )->join(
            array('category_store_table' => $this->getTable('category_store')),
            'category_table.category_id = category_store_table.category_id',
            array()
        )
        ->where('category_table.url_key = "'.$categoryId.'"')
        ->where('category_store_table.store_id in (?)', array(0, Mage::app()->getStore()->getId()))
        ;
        return $this;
    }

    public function addStoreFilter($store)
    {
        $this->getSelect()->join(
            array('blog_store_table' => $this->getTable('blog_store')),
            'main_table.blog_id = blog_store_table.blog_id',
            array()
        )
        ->where('blog_store_table.store_id in (?)', array(0, $store));
        $this->getSelect()->distinct();
        return $this;
    }
}
