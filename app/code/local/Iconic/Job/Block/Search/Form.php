<?php

class Iconic_Job_Block_Search_Form extends Mage_Core_Block_Template
{
	protected function _prepareLayout(){
		$this->setTemplate('job/search/form.phtml');
	}
	
	protected function _toHtml(){
		// if(count($this->getFilterCollection()) <= 1){ // if there are more than 1 item to filter
			// return '';
		// }
		return parent::_toHtml();
	}
	
	public function getLanguageList(){
		//get list location and category
		if (!$this->hasData('languageList')){
			$language = Mage::getModel('job/language')->getCollection();
			if ($this->getLanguage()){
				foreach ($language as $loc){
					$name = Mage::helper('job')->getTransName($loc);
					$selected = "";
					if($loc->getId() == $this->getLanguage()){
						$selected = " selected=\"selected\"";
					}
					$listLocation .= "<option value=\"{$loc->getId()}\"{$selected}>{$name}</option>";
				}
			} else {
				foreach ($language as $loc){
					$name = Mage::helper('job')->getTransName($loc);
					$listLanguage .= '<option value="' . $loc->getId() . '">' . $name . '</option>';
				}				
			}
			$this->setData('languageList', $listLanguage);
		}
		return $this->getData('languageList');
	}
	
	public function getCategoryList(){
		if (!$this->hasData('categoryList')){
		
			$parentCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'industry'));
			$listCategory = '';
			if ($this->getCategory()){
				foreach ($parentCategory as $parent){
					$parentname = Mage::helper('job')->getTransName($parent);
					$categories = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$parent->getParentcategoryId()));
					$catOptions = '';
					foreach ($categories as $cat){
						$catname = Mage::helper('job')->getTransName($cat);
						$selected = "";
						if($cat->getId() == $this->getCategory()){
							$selected = " selected=\"selected\"";
						}
						$catOptions .= "<option value=\"{$cat->getCategoryId()}\"{$selected}>{$catname}</option>";
					}
					$listCategory .= '<optgroup label="'.$parentname.'">'.$catOptions.'</optgroup>';
				}
			} else {
				foreach ($parentCategory as $parent){
					$parentname = Mage::helper('job')->getTransName($parent);
					$categories = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$parent->getParentcategoryId()));
					$catOptions = '';
					foreach ($categories as $cat){
						$catname = Mage::helper('job')->getTransName($cat);
						$catOptions .= '<option value="' . $cat->getCategoryId() . '">' . $catname . '</option>';
					}
					$listCategory .= '<optgroup label="'.$parentname.'">'.$catOptions.'</optgroup>';
				}
			}
			$this->setData('categoryList', $listCategory);
		}
		return $this->getData('categoryList');
	}

	public function getFunctionList(){
		if (!$this->hasData('functionList')){
		
			$parentCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'function'));
			$listCategory = '';
			if ($this->getFunctionCategory()){
				foreach ($parentCategory as $parent){
					$parentname = Mage::helper('job')->getTransName($parent);
					$categories = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$parent->getParentcategoryId()));
					$catOptions = '';
					foreach ($categories as $cat){
						$catname = Mage::helper('job')->getTransName($cat);
						$selected = "";
						if($cat->getId() == $this->getFunctionCategory()){
							$selected = " selected=\"selected\"";
						}
						$catOptions .= "<option value=\"{$cat->getCategoryId()}\"{$selected}>{$catname}</option>";
					}
					$listCategory .= '<optgroup label="'.$parentname.'">'.$catOptions.'</optgroup>';
				}
			} else {
				foreach ($parentCategory as $parent){
					$parentname = Mage::helper('job')->getTransName($parent);
					$categories = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$parent->getParentcategoryId()));
					$catOptions = '';
					foreach ($categories as $cat){
						$catname = Mage::helper('job')->getTransName($cat);
						$catOptions .= '<option value="' . $cat->getCategoryId() . '">' . $catname . '</option>';
					}
					$listCategory .= '<optgroup label="'.$parentname.'">'.$catOptions.'</optgroup>';
				}
			}
			$this->setData('functionList', $listCategory);
		}
		return $this->getData('functionList');
	}
}