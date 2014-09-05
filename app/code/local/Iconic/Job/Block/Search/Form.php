<?php

class Iconic_Job_Block_Search_Form extends Iconic_Job_Block_Search
{
	protected function _prepareLayout(){
		//var_dump($this->getMultiLanguage());die;
		$this->setTemplate('job/search/form.phtml');
	}
	
	protected function _toHtml(){
		// if(count($this->getFilterCollection()) <= 1){ // if there are more than 1 item to filter
			// return '';
		// }
		return parent::_toHtml();
	}
	
	public function getKeyword(){
		// get keyword search string
		//$keyword = Mage::getSingleton('core/session')->getKeywordSearch();
		$keyword = $this->getRequest()->getParam('q');
		return $keyword;
	}
	
	public function getMultiLanguage(){
		return $this->getRequest()->getParam('multilanguage');
	}
	
	public function getMultiLocation(){
		return $this->getRequest()->getParam('multilocation');
	}
	
	public function getMultiCategory(){
		return $this->getRequest()->getParam('multicategory');
	}
	
	public function getMultiFunction(){
		return $this->getRequest()->getParam('multifunction');
	}
	
	public function getLanguageList(){
		//get list location and category
		if (!$this->hasData('languageList')){
			$language = Mage::getModel('job/language')->getCollection();
			if ($this->getMultiLanguage()){
				foreach ($language as $loc){
					$name = Mage::helper('job')->getTransName($loc);
					$selected = "";
					if(in_array($loc->getId().'-1', $this->getMultiLanguage())){
						$selected = " selected=\"selected\"";
					}
					$listLanguage .= "<option value=\"{$loc->getId()}-1\"{$selected}>{$name}</option>";
				}
			} else {
				foreach ($language as $loc){
					$name = Mage::helper('job')->getTransName($loc);
					$listLanguage .= '<option value="' . $loc->getId() . '-1">' . $name . '</option>';
				}				
			}
			$this->setData('languageList', $listLanguage);
		}
		return $this->getData('languageList');
	}
	
	public function getLocationList(){
		//get list location and category
		if (!$this->hasData('locationList')){
			$countries = Mage::getModel('job/country')->getCollection();
			$listLocation = '';
			foreach($countries as $country){
				$selected = '';
				if(in_array($country->getId(), $this->getMultiLocation())){
					$selected = ' selected="selected"';
				}
				$listLocation .= '<option'. $selected .' value="'.$country->getId().'">'.Mage::helper('job')->getTransName($country).'</option>';		
			}	
			$this->setData('locationList', $listLocation);
		}
		return $this->getData('locationList');
	}
	
	public function getCategoryList(){
		if (!$this->hasData('categoryList')){
			$parentCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'industry'));
			$listCategory = '';
			foreach ($parentCategory as $parent){
				$parentname = Mage::helper('job')->getTransName($parent);
				$selected = '';
				if(in_array($parent->getId(), $this->getMultiCategory())){
					$selected = ' selected="selected"';
				}
				$listCategory .= '<option'. $selected .' value="'.$parent->getId().'">'.$parentname.'</option>';
			}
			$this->setData('categoryList', $listCategory);
		}
		return $this->getData('categoryList');
	}

	public function getFunctionList(){
		if (!$this->hasData('functionList')){
			$parentCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'function'));
			$listCategory = '';
			foreach ($parentCategory as $parent){
				$parentname = Mage::helper('job')->getTransName($parent);
				$selected = '';
				if(in_array($parent->getId(), $this->getMultiFunction())){
					$selected = ' selected="selected"';
				}
				$listCategory .= '<option'. $selected .' value="'.$parent->getId().'">'.$parentname.'</option>';
			}
			$this->setData('functionList', $listCategory);
		}
		return $this->getData('functionList');
	}
	
	public function getFeatureCollection(){
		return Mage::getModel('job/feature')->getCollection();
	}
}