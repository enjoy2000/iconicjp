<?php
class Iconic_Job_SearchController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
        $this->loadLayout();
		$searchBlock = $this->getLayout()->getBlock("job_search");

		$q = $this->getRequest()->get("q");
		if($q){
			$searchBlock->setKeyword($q);
		}
		
		$category = $this->getRequest()->get("category");
		if($category){
			$searchBlock->setCategory((int)$category);
		}
		
		$language = $this->getRequest()->get("language");
		if($language){
			$searchBlock->setLanguage($language);
		}
		
		$location = $this->getRequest()->get("location");
		if($location){
			$searchBlock->setLocation((int)$location);
		}
		
		$level = $this->getRequest()->get("level");
		if($level){
			$searchBlock->setJobLevel((int)$level);
		}
		
		$feature = $this->getRequest()->get("feature");
		if($feature){
			$searchBlock->setFeature((int)$feature);
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
		
		$multilocation = $this->getRequest()->get("multilocation");
		if($multilocation){
			$searchBlock->setMultiLocation($multilocation);
		}
		
		$multilanguage = $this->getRequest()->get("multilanguage");
		if($multilanguage){
			$searchBlock->setMultiLanguage($multilanguage);
		}
		
		$multicategory = $this->getRequest()->get("multicategory");
		if($multicategory){
			$searchBlock->setMultiCategory($multicategory);
		}
		
		$multifunction = $this->getRequest()->get("multifunction");
		if($multifunction){
			$searchBlock->setMultiFunction($multifunction);
		}
		
		$this->renderLayout();
	}
	
	public function searchformAction(){
		$data = $this->getRequest()->getPost();
		$url = Mage::helper('job')->getSearchUrl();
		
		// Check all parameters have single value, then use friendly URL
		if((count($data['multilocation']) < 2) && (count($data['multilanguage']) < 2) && (count($data['multicategory']) < 2) && (count($data['multifunction']) < 2)){
			if($data['multilocation']){
				$url .= '/' . Mage::getModel('job/country')->load($data['multilocation'])->getUrlKey();
			}
			if($data['multilanguage']){
				$url .= '/' . Mage::getModel('job/language')->load($data['multilanguage'])->getUrlKey();
			}
			if($data['multicategory']){
				$url .= '/' . Mage::getModel('job/parentcategory')->load($data['multicategory'])->getUrlKey();
			}
			if($data['multifunction']){
				$url .= '/' . Mage::getModel('job/parentcategory')->load($data['multifunction'])->getUrlKey();
			}
			if($data['q']){
				$url .= '/' . Mage::helper('job')->formatUrlKeyJp($data['q']);
				Mage::getSingleton('core/session')->setKeywordSearch($data['q']);
			}else{
				Mage::getSingleton('core/session')->unsKeywordSearch();
			}
			$url .= '/';
			if(Mage::app()->getStore()->getCode() == 'jp'){
				$base = Mage::getBaseUrl();
			}else{
				$base = Mage::getBaseUrl().'en/';
			}
			
			$newurl = $base.$url;
			header("Location: {$newurl}");
			die();
		}else{
			//var_dump($data);die;
			$newurl = Mage::getUrl('job/search/index', array('_query'=> $data));
			header("Location: {$newurl}");
			die;
		}
	}
}