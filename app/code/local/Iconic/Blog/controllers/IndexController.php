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

class Iconic_Blog_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        //// check if this category is allowed to view
        if ($category = $this->getRequest()->getParam('category')) {
            $collection = Mage::getModel('blog/category')->getCollection()
                ->addFieldToFilter('url_key', $category)
                ->addStoreFilter(Mage::app()->getStore()->getId());
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('blog')->getRoute());
            }
        }
        if ($tag = $this->getRequest()->getParam('q')) {
            $collection = Mage::getModel('blog/blog')->getCollection()
                            ->setOrder('blog_time', 'asc');
            if (count(Mage::app()->getStores()) > 1) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('blog_blog_store');
                $collection->getSelect()->join($tableName, 'main_table.blog_id = ' . $tableName . '.blog_id','store_id');
                $collection->getSelect()->where($tableName . '.store_id =?', Mage::app()->getStore()->getId());
            }
            $tag = urldecode($tag);
            $collection->getSelect()->where("main_table.tags LIKE '%". $tag . "%'");
            if (count($collection) < 1) {
                $this->_redirect(Mage::helper('blog')->getRoute());
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }
}
