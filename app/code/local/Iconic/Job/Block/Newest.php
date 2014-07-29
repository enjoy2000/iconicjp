<?php

class Iconic_Job_Block_Newest extends Mage_Core_Block_Template
{
    protected function _prepareLayout(){			
		
		parent::_prepareLayout();
		
		//set collection to view
		$jobs = Mage::getModel('job/job')->getCollection()
						->addFieldToFilter('status', array('like'=>'active'))
						->setOrder('created_time','DESC');
		$jobs->setPageSize(10);
		$jobs->setCurPage(1);
		$this->setJobCollection($jobs);
		
		//set Category to view
		$industryCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category',array('eq'=>'industry'));
		$functionCategory = Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category',array('eq'=>'function'));
		$this->setIndustryCollection($industryCategory);
		$this->setFunctionCollection($functionCategory);
		
		//set Country to view
		$country = Mage::getModel('job/country')->getCollection();
		$this->setCountryCollection($country);
		
		//set job language to view
		$lang = Mage::getModel('job/language')->getCollection();
		$this->setLanguageCollection($lang);
	}
}