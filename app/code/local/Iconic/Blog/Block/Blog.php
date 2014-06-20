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

class Iconic_Blog_Block_Blog extends Iconic_Blog_Block_Abstract
{
    protected function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            // show breadcrumbs
            $moduleName = $this->getRequest()->getModuleName();
            $showBreadcrumbs = (int)Mage::getStoreConfig('blog/blog/showbreadcrumbs');
            if ($showBreadcrumbs && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) && ($moduleName=='blog')) {
                $breadcrumbs->addCrumb('home',
                    array(
                    'label'=>Mage::helper('blog')->__('Trang chủ'),
                    'title'=>Mage::helper('blog')->__('Trang chủ'),
                    'link'=> Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
                $blogBreadCrumb = array(
                    'label'=>Mage::helper('blog')->__(Mage::getStoreConfig('blog/blog/title')),
                    'title'=>Mage::helper('blog')->__(Mage::getStoreConfig('blog/blog/title')),
                    'link' => Mage::getUrl(Mage::helper('blog')->getRoute())
                    );
                if ($this->getCategoryKey()) {
                    $blogBreadCrumb['link'] = Mage::getUrl(Mage::helper('blog')->getRoute());
                }
                $breadcrumbs->addCrumb('blog', $blogBreadCrumb);

                if ($this->getCategoryKey()) {
                    $categories = Mage::getModel('blog/category')
                        ->getCollection()
                        ->addFieldToFilter('url_key', $this->getCategoryKey())
                        ->setPageSize(1);
                    $category = $categories->getFirstItem();
                    $breadcrumbs->addCrumb('category',
                        array(
                        'label'=>$category->getTitle(),
                        'title'=>Mage::helper('blog')->__('Go to Home Page'),
                        ));
                }
            }

            if ($moduleName=='blog') {
                // set default meta data
                $head->setTitle(Mage::getStoreConfig('blog/blog/metatitle'));
                $head->setKeywords(Mage::getStoreConfig('blog/blog/metakeywords'));
                $head->setDescription(Mage::getStoreConfig('blog/blog/metadescription'));

                // set category meta data if defined
                $currentCategory = $this->getCurrentCategory();
                if ($currentCategory!=null) {
                    if ($currentCategory->getTitle()!='') {
                        $head->setTitle($currentCategory->getTitle());
                    }
                    if ($currentCategory->getMetaKeywords()!='') {
                        $head->setKeywords($currentCategory->getMetaKeywords());
                    }
                    if ($currentCategory->getMetaDescription()!='') {
                        $head->setDescription($currentCategory->getMetaDescription());
                    }
                }
            }
        }
    }

    public function getShortImageSize($item)
    {
        $width_max = Mage::getStoreConfig('blog/blog/shortdescr_image_max_width');
        $height_max = Mage::getStoreConfig('blog/blog/shortdescr_image_max_height');
        if (Mage::getStoreConfig('blog/blog/resize_to_max') == 1) {
            $width = $width_max;
            $height = $height_max;
        } else {
            $imageObj = new Varien_Image(Mage::getBaseDir('media') . DS . $item->getImageShortContent());
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
        if ($item->getShortWidthResize()): $width = $item->getShortWidthResize(); else: $width; endif;
        if ($item->getShortHeightResize()): $height = $item->getShortHeightResize(); else: $height; endif;

        return array('width' => $width, 'height' => $height);
    }
}
