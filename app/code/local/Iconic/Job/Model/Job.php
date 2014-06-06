<?php
 
class Iconic_Job_Model_Job extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('job/job');
    }
    
    protected function _beforeSave()
    {
        if(!$this->getUrlKey()){
            $urlKey = Mage::helper('job')->formatUrlKeyJp($this->getTitle());
            if(!Mage::getModel('job/job')->load($urlKey, 'url_key')->getId()){
                $this->setUrlKey($urlKey);
            } else {
                $urlKey .= '-' . $this->getId();
            	$this->setUrlKey($urlKey);
            }
        }
        parent::_beforeSave();
    }
    
    protected function _afterSave()
    {
        parent::_afterSave();
    } 

    public function getCategory(){
        return Mage::getModel('job/category')->load($this->getCategoryId());
    }
	
	
	public function getJobsInCategory($limit=20){
		$category = $this->getCategory();
		$jobsInCategory = Mage::getModel('job/job')->getCollection()->addFieldToFilter('category_id',array('eq'=>$this->getCategoryId()))
					->addFieldToFilter('job_id',array('neq'=>$this->getId()))
					->setOrder('created_time','DESC')
					->setPageSize($limit)
					->setCurPage(1)
					->load();
		return $jobsInCategory;
	}
	
	public function getWorkLocation(){
		return $this->getLocationText();
	}
	
	public function getCountry(){
		$location = Mage::getModel('job/location')->load($this->getLocationId());
		$country = Mage::getModel('job/country')->load($location->getCountryId());
		if(Mage::app()->getStore()->getCode() == 'jp'){
			return $country->getName();
		}else{
			return $country->getNameEn();
		}
	}
	
	public function getCategoryName(){
		$category = Mage::getModel('job/category')->load($this->getCategoryId());
		if(Mage::app()->getStore()->getCode() == 'jp'){
			return $category->getName();
		}else{
			return $category->getNameEn();
		}
	}
	
	public function getFunctionName(){
		$function = Mage::getModel('job/category')->load($this->getFunctionCategoryId());
		if(Mage::app()->getStore()->getCode() == 'jp'){
			return $function->getName();
		}else{
			return $function->getNameEn();
		}
	}
	
	public function getLanguage(){
		$langs = explode(',', substr($this->getLanguageId(),1,-1));
		if(Mage::app()->getStore()->getCode() == 'jp'){
			foreach($langs as $lang){
				$langName[] = Mage::getModel('job/language')->load((int)$lang)->getName();
			}
			return implode(',', $langName);
		}else{
			foreach($langs as $lang){
				$langName[] = Mage::getModel('job/language')->load((int)$lang)->getNameEn();
			}
			return implode(',', $langName);
		}
	}
	
	public function getLevel(){
		$level = Mage::getModel('job/level')->load($this->getJobLevel());
		if(Mage::app()->getStore()->getCode() == 'jp'){
			return $level->getName();
		}else{
			return $level->getNameEn();
		}
	}
	
	public function getFullSalary(){
		if($this->getJobSalary() && $this->getJobSalaryTo()){
			$salary = $this->getJobSalary() . ' - ' . $this->getJobSalaryTo() . '(' .$this->getJobSalaryType() . ')'; 
		}else if($this->getJobSalary() && !$this->getJobSalaryTo()){
			$salary = $this->getJobSalary() . '(' .$this->getJobSalaryType() . ')'; 
		}else{
			$salary = Mage::helper('job')->__('Negotiable');
		}
		return $salary;
	}
	
	public function getUrl(){
		$category = Mage::getModel('job/category')->load($this->getCategoryId());
		$parentCategory = Mage::getModel('job/parentcategory')->load($category->getParentcategoryId());
		if(Mage::app()->getStore()->getCode() == 'jp'){
			$url = Mage::getBaseUrl() . $parentCategory->getUrlKey().'/'.$category->getUrlKey().'/'.$this->getUrlKey();
		}else{
			$url = Mage::getBaseUrl() . 'en/' . $parentCategory->getUrlKey().'/'.$category->getUrlKey().'/'.$this->getUrlKey();
		}

		return $url;
	}
	
	public function getApplyUrl(){
		if(Mage::app()->getStore()->getCode() == 'jp'){
			$url = Mage::getBaseUrl() . 'job/apply?id=' . $this->getId();
		}else{
			$url = Mage::getBaseUrl() . 'en/' . 'job/apply?id=' . $this->getId();
		}
		
		return $url;
	}
}