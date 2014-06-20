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

class Iconic_Blog_Model_Mysql4_Comment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/comment');
    }

    public function addApproveFilter($status)
    {
        $this->getSelect()
            ->where('comment_status = ?', $status);
        return $this;
    }

    public function addBlogFilter($blogId)
    {
        $this->getSelect()
            ->where('blog_id = ?', $blogId);
        return $this;
    }
}
