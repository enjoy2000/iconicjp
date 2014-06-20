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

class Iconic_Blog_Block_Blogitem extends Mage_Core_Block_Template
{
    protected $_pagesCount = null;
    protected $_currentPage = null;
    protected $_itemsOnPage = 10;
    protected $_pages;

    protected function _construct()
    {
        $this->_currentPage = $this->getRequest()->getParam('page');
        if (!$this->_currentPage) {
            $this->_currentPage=1;
        }

        $itemsPerPage = (int)Mage::getStoreConfig('blog/comments/commentsperpage');
        if ($itemsPerPage > 0) {
            $this->_itemsOnPage = $itemsPerPage;
        }
    }

    protected function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            $blogitem = $this->getBlogItem();

            if ($blogitem==null) {
                return;
            }
            $showBreadcrumbs = (int)Mage::getStoreConfig('blog/blog/showbreadcrumbs');
            if ($showBreadcrumbs && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
                $breadcrumbs->addCrumb('home',
                    array(
                    'label'=>Mage::helper('job')->__('Trang chủ'),
                    'title'=>Mage::helper('blog')->__('Trang chủ'),
                    'link'=>Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)
                    ));

                $breadcrumbs->addCrumb('blog',
                    array(
                    'label'=>Mage::helper('blog')->__(Mage::getStoreConfig('blog/blog/title')),
                    'title'=>Mage::helper('blog')->__('Return to %s', Mage::helper('blog')->__('Blog')),
                    'link'=> Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'blog'
                    ));

                if ($category = $blogitem->getCategory()) {
                    $breadcrumbs->addCrumb('category',
                    array(
                    'label' => $category->getTitle(),
                    'title' => Mage::helper('blog')->__('Return to %s', $category->getTitle()),
                    'link' => Mage::getUrl(Mage::helper('blog')->getRoute()).'category/'.$category->getUrlKey().Mage::helper('blog')->getBlogitemUrlSuffix()
                        ));
                }

                $breadcrumbs->addCrumb('item',
                    array(
                    'label'=>$blogitem->getTitle(),
                    'title'=>$blogitem->getTitle()
                    ));
            }

            $head->setTitle($blogitem->getTitle());
            if ($blogitem->getMetaKeywords()!='') {
                $head->setKeywords($blogitem->getMetaKeywords());
            } else {
                $head->setKeywords(Mage::getStoreConfig('blog/blog/metakeywords'));
            }
            if ($blogitem->getMetaDescription()!='') {
                $head->setDescription($blogitem->getMetaDescription());
            } else {
                $head->setDescription(Mage::getStoreConfig('blog/blog/metadescription'));
            }
        }
		return parent::_prepareLayout();
    }

    public function getBlogItem()
    {
        return Mage::registry('blogitem');
    }

    public function getComments()
    {
        $blogitem = $this->getBlogItem();

        $collection = Mage::getModel('blog/comment')->getCollection()
            ->addBlogFilter($blogitem->getBlogId())
            ->addApproveFilter(Iconic_Blog_Helper_Data::APPROVED_STATUS)
            ->setOrder('created_time ', 'asc');
        $this->_pagesCount = ceil($collection->getSize()/$this->_itemsOnPage);
        for ($i=1; $i<=$this->_pagesCount;$i++) {
            $this->_pages[] = $i;
        }
        $this->setLastPageNum($this->_pagesCount);

        $collection->setPageSize($this->_itemsOnPage);
        $collection->setCurPage($this->_currentPage);

        return $collection;
    }

    public function getRequireLogin()
    {
        return Mage::getStoreConfig('blog/comments/need_login');
    }

    public function getImageUrl()
    {
        $blogitem = $this->getBlogItem();
        $image = Mage::getBaseUrl('media'). DS .$blogitem->getImage();
        return $image;
    }

    public function getBackUrl()
    {
        $blogitem = $this->getBlogItem();
        if ($category = $blogitem->getCategory()) {
            return Mage::getUrl(Mage::helper('blog')->getRoute()).'category/'.$category->getUrlKey().Mage::helper('blog')->getBlogitemUrlSuffix();
        } else {
            return $this->getUrl(Mage::helper('blog')->getRoute());
        }
    }

    public function getCategoryByBlog($id)
    {
        $data = Mage::getModel('blog/category')->getCategoryByBlogId($id);
        $data = new Varien_Object($data);
        $collection = Mage::getModel('blog/category')->getCollection()
        ->addStoreFilter(Mage::app()->getStore()->getId());
        if ($data->getData('0/category_id')!= NULL) {
            $collection->getSelect()->where('main_table.category_id =' . $data->getData('0/category_id'));
            $category = $collection->getFirstItem();
            return $category;
        } else {
            $category = $collection->getFirstItem();
            return $category->setData('title','');
        }
    }

    public function isFirstPage()
    {
        if ($this->_currentPage==1) {
            return true;
        }
        return false;
    }

    public function isLastPage()
    {
        if ($this->_currentPage==$this->_pagesCount) {
            return true;
        }
        return false;
    }

    public function isPageCurrent($page)
    {
        if ($page==$this->_currentPage) {
            return true;
        }
        return false;
    }

    public function getPageUrl($page, $id)
    {
        if ($category = $this->getRequest()->getParam('category')) {
            return $this->getUrl('*/blogitem/view', array('category' => $category, 'id' => $id, 'page' => $page));
        } else {
            return $this->getUrl('*/blogitem/view', array('id' => $id, 'page' => $page));
        }
    }

    public function getNextPageUrl()
    {
        $page = $this->_currentPage+1;
        return $this->getPageUrl($page);
    }

    public function getPreviousPageUrl($id)
    {
        $page = $this->_currentPage-1;
        return $this->getPageUrl($page, $id);
    }

    public function getPages()
    {
        return $this->_pages;
    }

    public function getPrintLogoUrl ()
    {
        // load html logo
        $logo = Mage::getStoreConfig('sales/identity/logo_html');
        if (!empty($logo)) {
            $logo = 'sales/store/logo_html/' . $logo;
        }

        // load default logo
        if (empty($logo)) {
            $logo = Mage::getStoreConfig('sales/identity/logo');
            if (!empty($logo)) {
                // prevent tiff format displaying in html
                if (strtolower(substr($logo, -5)) === '.tiff' || strtolower(substr($logo, -4)) === '.tif') {
                    $logo = '';
                }
                else {
                    $logo = 'sales/store/logo/' . $logo;
                }
            }
        }

        // buld url
        if (!empty($logo)) {
            $logo = Mage::getStoreConfig('web/unsecure/base_media_url') . $logo;
        }
        else {
            $logo = '';
        }

        return $logo;
    }

    public function getPrintLogoText()
    {
        return Mage::getStoreConfig('sales/identity/address');
    }

    public function getLang()
    {
        if (!$this->hasData('lang')) {
            $this->setData('lang', substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2));
        }
        return $this->getData('lang');
    }

    public function getAbsoluteFooter()
    {
        return Mage::getStoreConfig('design/footer/absolute_footer');
    }

    public function getBodyClass()
    {
        return $this->_getData('body_class');
    }

    public function contentFilter($content)
    {
        $helper = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $html = $processor->filter($content);
        //$html = $this->getMessagesBlock()->getGroupedHtml() . $html;
        return $html;
    }

    public function getFullImageSize($item)
    {
        $width_max = Mage::getStoreConfig('blog/blog/fulldescr_image_max_width');
        $height_max = Mage::getStoreConfig('blog/blog/fulldescr_image_max_height');
        if (Mage::getStoreConfig('blog/blog/resize_to_max') == 1) {
            $width = $width_max;
            $height = $height_max;
        } else {
            $imageObj = new Varien_Image(Mage::getBaseDir('media') . DS . $item->getImageFullContent());
            $original_width = $imageObj->getOriginalWidth();
            $original_height = $imageObj->getOriginalHeight();
            if ($original_width > $width_max) {
                $width = $width_max;
            } else {
                $width = $original_width;
            }
            if ($original_height > $height_max) {
                $height = $height_max;
            } else {
                $height = $original_height;
            }
        }
        if ($item->getFullWidthResize()): $width = $item->getFullWidthResize(); else: $width; endif;
        if ($item->getFullHeightResize()): $height = $item->getFullHeightResize(); else: $height; endif;

        return array('width' => $width, 'height' => $height);
    }
}
