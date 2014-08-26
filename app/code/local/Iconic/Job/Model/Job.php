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
    	// check url key is set or not; if not generate url based on title then set
        if(!$this->getUrlKey()){
            $urlKey = Mage::helper('job')->formatUrlKeyJp($this->getTitle());
            if(!Mage::getModel('job/job')->load($urlKey, 'url_key')->getId()){
                $this->setUrlKey($urlKey);
            } else {
                $urlKey .= '-' . $this->getId();
            	$this->setUrlKey($urlKey);
            }
        }
		// check update time or set or not; if not set current time
		// this is for employer job post when I'm lazy
		if(!$this->getUpdateTime()){
			$currentTime = Date('Y-m-d H:i:s');
			$this->setUpdateTime($currentTime);
		}
        parent::_beforeSave();
    }
    
    protected function _afterSave()
    {
    	// call function for insert new row to Job table
    	//this is for sync to IS system
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
		$location = explode(',', substr($this->getLocationId(),1,-1));
		$location = Mage::getModel('job/country')->load($location[0]);
		return Mage::helper('job')->getTransName($location);
	}
	
	public function getCategoryName(){
		$category = Mage::getModel('job/category')->load($this->getCategoryId());
		$parent = Mage::getModel('job/parentcategory')->load($category->getParentcategoryId());
		return Mage::helper('job')->getTransName($parent);
	}
	
	public function getFunctionName(){
		$function = Mage::getModel('job/category')->load($this->getFunctionCategoryId());
		$parent = Mage::getModel('job/parentcategory')->load($function->getParentcategoryId());
		return Mage::helper('job')->getTransName($parent);
	}
	
	public function getLanguage(){
		$langs = explode(',', substr($this->getLanguageId(),1,-1));
		$names = array();
		foreach($langs as $lang){
			$lang = explode('-', $lang);
			$langModel = Mage::getModel('job/language')->load(intval($lang[0]));
			$name = Mage::helper('job')->getTransName($langModel);
			$name .= ': ';
			$level = Mage::getModel('job/langlevel')->load(intval($lang[1]));
			$levelname = Mage::helper('job')->getTransName($level);
			$name .= $levelname;
			$names[] = $name;
		}
		return implode('<br />', $names);
	}
	
	public function getLevel(){
		$level = Mage::getModel('job/level')->load($this->getJobLevel());
		return Mage::helper('job')->getTransName($level);
	}
	
	public function getType(){
		$type = Mage::getModel('job/type')->load($this->getJobType());
		return Mage::helper('job')->getTransName($type);
	}
	
	public function getFullSalary(){
		if($this->getJobSalary() && $this->getJobSalaryTo()){
			$salary = $this->getJobSalaryCurrency() . $this->getJobSalary() . ' - ' . $this->getJobSalaryTo() . '(' .$this->getJobSalaryType() . ')'; 
		}else if($this->getJobSalary() && !$this->getJobSalaryTo()){
			$salary = $this->getJobSalaryCurrency() . $this->getJobSalary() . '(' .$this->getJobSalaryType() . ')'; 
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
		$url = Mage::getUrl('job/apply/index', array('id'=>$this->getId()));
		return $url;
	}
	
	public function getEditUrl(){
		$url = Mage::getUrl('client/job/post', array('id'=>$this->getId()));
		return $url;
	}
}