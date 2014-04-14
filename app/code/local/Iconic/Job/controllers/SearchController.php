<?php
class Iconic_Job_SearchController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
        $this->loadLayout();
		$searchBlock = $this->getLayout()->getBlock("job_search");

		$q = $this->getRequest()->get("q");
		$searchBlock->setKeyword($q);
		
		$category = $this->getRequest()->get("category");
		if($category){
			$searchBlock->setCategory((int)$category);
		}
		
		$location = $this->getRequest()->get("location");
		if($location){
			$searchBlock->setLocation((int)$location);
		}
		
		$level = $this->getRequest()->get("level");
		if($level){
			$searchBlock->setJobLevel((int)$level);
		}
		
		$functionCategory = $this->getRequest()->get("function_category");
		if($functionCategory){
			$searchBlock->setFunctionCategory((int)$functionCategory);
		}
		
		$industry = $this->getRequest()->get("industry");
		if($industry){
			$searchBlock->setIndustry((int)$industry);
		}
		
		$function = $this->getRequest()->get("function");
		if($function){
			$searchBlock->setFunction((int)$function);
		}
		
		$this->renderLayout();
	}
	
	public function searchformAction(){
		$request = $this->getRequest();
		$url = Mage::helper('job')->getSearchUrl();
		if($request->get('location')){
			$url .= '/' . Mage::getModel('job/location')->load($request->get('location'))->getUrlKey();
		}
		if($request->get('category')){
			$url .= '/' . Mage::getModel('job/category')->load($request->get('category'))->getUrlKey();
		}
		if($request->get('function_category')){
			$url .= '/' . Mage::getModel('job/category')->load($request->get('function_category'))->getUrlKey();
		}
		if($request->get('q')){
			$url .= '/' . Mage::helper('job')->formatUrlKey($request->get('q'));
			Mage::getSingleton('core/session')->setKeywordSearch($request->get('q'));
		}else{
			Mage::getSingleton('core/session')->unsKeywordSearch();
		}
		$url .= '/';
		$base = Mage::getBaseUrl();
		$url = $base.$url;
		header("Location: {$url}");
		die();
	}
}