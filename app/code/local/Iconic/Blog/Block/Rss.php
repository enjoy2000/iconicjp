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

class Iconic_Blog_Block_Rss extends Mage_Rss_Block_Abstract
{
    protected function _toHtml()
    {
        $rssObj = Mage::getModel('rss/rss');

        $data = array('title' => 'Blog',
            'description' => 'Blog',
            'link'        => $this->getUrl('blog/rss'),
            'charset'     => 'UTF-8',
            'language'    => Mage::getStoreConfig('general/locale/code')
            );

        $rssObj->_addHeader($data);

        $collection = Mage::getModel('blog/blog')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->setOrder('created_time ', 'desc');

        $categoryId = $this->getRequest()->getParam('category');

        if ($categoryId && $category = Mage::getSingleton('blog/category')->load($categoryId)) {
            $collection->addCategoryFilter($category->getUrlKey());
        }

        $collection->setPageSize((int)Mage::getStoreConfig('blog/rss/posts'));
        $collection->setCurPage(1);

        if ($collection->getSize()>0) {
            foreach ($collection as $item) {
                $data = array(
                            'title'         => $item->getTitle(),
                            'link'          => $this->getUrl("blog/blogitem/view", array("id" => $item->getId())),
                            'description'   => $item->getShortContent(),
                            'lastUpdate'    => strtotime($item->getBlogTime()),
                            );

                $rssObj->_addEntry($data);
            }
        } else {
             $data = array('title' => Mage::helper('blog')->__('Cannot retrieve the blog'),
                    'description' => Mage::helper('blog')->__('Cannot retrieve the blog'),
                    'link'        => Mage::getUrl(),
                    'charset'     => 'UTF-8',
                );
             $rssObj->_addHeader($data);
        }

        return $rssObj->createRssXml();
    }
}
