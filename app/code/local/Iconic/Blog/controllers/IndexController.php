<?php
class Iconic_Blog_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
		
		//breadcrumbs
		$helper = Mage::helper('blog');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('blog', array('label'=>$helper->__('ブログ'), 'title'=>$helper->__('ブログ'), 'link'=>Mage::getUrl(Mage::helper('blog')->getRoute())));
		}
		
		$head = $this->getLayout()->getBlock('head');
		$catBlock = $this->getLayout()->getBlock('category');
		$tit = 'BLOG';
		if($parent = $this->getRequest()->getParam('parent')){
			$parentArr = array($parent);
			$catBlock->setParent($parentArr);
			$parent = Mage::getModel('blog/parentcategory')->load($parent);
			$tit .= Mage::helper('job')->getTransName($parent);
			$breadcrumbs->addCrumb('parent', array('label'=>Mage::helper('job')->getTransName($parent), 'title'=>Mage::helper('job')->getTransName($parent), 'link'=>$parent->getUrl()));
		}
		if($cat = $this->getRequest()->getParam('cat')){
			$catArr = array($cat);
			$catBlock->setCat($catArr);
			$cat = Mage::getModel('blog/category')->load($cat);
			$tit .= Mage::helper('job')->getTransName($cat);
			$breadcrumbs->addCrumb('cat', array('label'=>Mage::helper('job')->getTransName($cat)));
		}
		$head->setTitle($tit);
		
		$this->getRequest()->getParams();
		
        $this->renderLayout();
    }
	
	public function ajaxAction(){
		$collection = Mage::getModel('blog/blog')->getCollection();
		
		if($parentId = $this->getRequest()->getParam('parent')){
			$parent = Mage::getModel('job/parentcategory')->load($parentId);
			$cats = Mage::getModel('blog/category')->getCollection()->addFieldToFilter('parentcategory_id',array('eq'=>$parent->getId()));
			if($cats->count() > 0){
				$condition = array();
				foreach($cats as $cat){
					$condition[] = array('like' => '%,'.(int)$cat->getId().',%');
				}
				$collection->addFieldToFilter('category_id',$condition);
			}else{
				$collection =  new Varien_Data_Collection(); //no sub category
			}
		}
		if($cat = $this->getRequest()->getParam('cat')){
			$collection->addFieldToFilter('category_id',array('like'=>'%,'.$cat.',%'));
		}
		
		if($keyword = $this->getRequest()->getParam('q')){
			$likeStm = array('like'=> "%{$keyword}%");
			$collection->addFieldToFilter(
							array('title', 'full_content'),
							array($likeStm, $likeStm));
		}
		
		//set page for ajax load
		$collection->setPageSize(8);
		if(($page = $this->getRequest()->getParam('page')) && ($collection->count() > 8)){
			$collection->setCurPage($page);
		}else{
			$collection->setCurPage(1);
		}
		if(($collection->count()%8 == 0) && ($page == $collection->count()/8)){
			$json['nomore'] = 1;
		}
		$collection->setOrder('create_time','DESC');
		
		//convert to json
		$json = array();
		$json['page'] = $page;
		$json['count'] = $collection->count();
		foreach($collection as $blog){
			$author = Mage::getModel('blog/author')->load($blog->getAuthorId());
			$item = array();
			
			//get parent category list
			$parents = substr($blog->getCategoryId(), 1, -1);
			$parents = explode(',', $parents);
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
			
			//social counter
			//$url = $blog->getUrl();
			//$apikey = "915d5146714fd0c40e73cfc5b898ae7ed2105b11";
			//$count = file_get_contents("http://free.sharedcount.com/?url=" . rawurlencode($url) . "&apikey=" . $apikey);
			//$counts = json_decode($count, true);
			$url = $blog->getUrl();
			$counts = Mage::helper('blog')->getShareCount($url);
			
			$item['social'] = $counts;
			$item['url'] = $blog->getUrl();
			$item['image'] = $blog->getImage();
			$item['title'] = $blog->getTitle();
			$item['date'] = $blog->getDate();
			$item['author']['name'] = $author->getName();
			$item['author']['link'] = $author->getLink();
			$item['author']['image'] = $author->getImage();
			$json['items'][] = $item;
			//array_push($json, $item);
		}
		Mage::getSingleton('core/session')->unsBlogSearch();
		$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        $this->getResponse()->setBody(json_encode($json));
	}

	public function searchAction(){
		if($keyword = $this->getRequest()->getParam('q')){
			Mage::getSingleton('core/session')->setBlogSearch($keyword);
			$this->_redirect(Mage::helper('blog')->getRoute() . DS . Mage::helper('job')->formatUrlKeyJp($keyword));
			return;
		}else{
			Mage::getSingleton('core/session')->unsBlogSearch();
		}
	}
	
}