<?php
class Iconic_Blog_DetailController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
		if($id = $this->getRequest()->getParam('id')){
			$catBlock = $this->getLayout()->getBlock('category');
			$head = $this->getLayout()->getBlock('head');
			if($parent = $this->getRequest()->getParam('parent')){
				$catBlock->setParent($parent);
			}
			if($cat = $this->getRequest()->getParam('cat')){
				$catBlock->setCat($cat);
			}
			$blog = Mage::getModel('blog/blog')->load($id);
			
			//breadcrumbs
			$helper = Mage::helper('blog');
			if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
				$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
				$breadcrumbs->addCrumb('blog', array('label'=>$helper->__('ブログ'), 'title'=>$helper->__('ブログ'), 'link'=>Mage::getUrl(Mage::helper('blog')->getRoute())));
				$breadcrumbs->addCrumb('blog_title', array('label'=>$blog->getTitle(), 'title'=>$blog->getTitle()));
			}
			
			//set head
			$head->setTitle($blog->getTitle());
			if($blog->getMetaDescription()){
				$head->setDescription($blog->getMetaDescription());
			}
			if($blog->getKeywords()){
				$head->setKeywords($blog->getKeywords());
			}
			
			$detailBlock = $this->getLayout()->getBlock('detail');
			$detailBlock->setBlog($blog);
			
			//get parent category list
			$parents = substr($blog->getCategoryId(), 1, -1);
			$parents = explode(',', $parents);
			$detailBlock->setCatIds($parents);
			$parentArr = array();
			foreach($parents as $catId){
				$catModel = Mage::getModel('blog/category')->load($catId);
				$parent = Mage::getModel('blog/parentcategory')->load($catModel->getParentcategoryId());
				if(!in_array($parent->getId(), $parentArr)){
					$cat = array();
					$cat['name'] = Mage::helper('job')->getTransName($parent);
					$cat['class'] = $parent->getUrlKey();
					$item['category'][] = $cat;
					$parentArr[] = $parent->getId();
				}
			}
			$detailBlock->setParentIds($parentArr);
			$blog->setViews($blog->getViews() + 1)->save();
		}
        $this->renderLayout();
    }
}