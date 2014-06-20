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

class Iconic_Blog_Model_Category extends Mage_Core_Model_Abstract
{
    protected function _construct(){
        parent::_construct();
        $this->_init('blog/category');
    }

    public function getCategoryByBlogId($id)
    {
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $select = $db->select()
             ->from(array(Mage::getSingleton('core/resource')->getTableName('blog_blog_category')),
                    array('category_id'))
             ->where('blog_id = ?', $id);
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();
        return $result;
    }

}
