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

class Iconic_Blog_Model_Blog extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('blog/blog');
    }

    public function getUrl($category = '') {
        if ($category) {
            $url = Mage::getUrl(Mage::helper('blog')->getRoute()).$category.'/'.$this->getUrlKey().Mage::helper('blog')->getBlogitemUrlSuffix();
        } else {
            $url = Mage::getUrl(Mage::helper('blog')->getRoute()).$this->getUrlKey().Mage::helper('blog')->getBlogitemUrlSuffix();
        }
        return $url;
    }

    /**
     * Reset all model data
     *
     * @return Iconic_Blog_Model_Blog
     */
    public function reset()
    {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;
        return $this;
    }
}
