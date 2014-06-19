<?php

class Iconic_Job_Block_Search extends Mage_Core_Block_Template
{
	protected function _prepareLayout(){
		$helper = Mage::helper('job');
		$tit = '';
			if($keyword = $this->getRequest()->get('q')){
				$tit .= $keyword;
			}
			if($langId = $this->getRequest()->get('language')){
				$lang = Mage::getModel('job/language')->load($langId);
				$langname = Mage::helper('job')->getTransName($lang);
				$tit .= $langname;
			}
			if($catId = $this->getRequest()->get('location')){
				$loc = Mage::getModel('job/country')->load($catId);
				$locname = Mage::helper('job')->getTransName($loc);
				$tit .= $locname;
			}
			if($industryId = $this->getRequest()->get('industry')){
				$cat = Mage::getModel('job/parentcategory')->load($industryId);
				$industryname = Mage::helper('job')->getTransName($cat);
				$tit .= $industryname;
			}
			if($functionId = $this->getRequest()->get('industry')){
				$cat = Mage::getModel('job/parentcategory')->load($functionId);
				$functionname = Mage::helper('job')->getTransName($cat);
				$tit .= $functionname;
			}
			if($featureId = $this->getRequest()->get('feature')){
				$feature = Mage::getModel('job/feature')->load($featureId);
				$featurename = Mage::helper('job')->getTransName($feature);
				$tit .= $featurename;
			}
			$tit .= $helper->__('の求人検索結果');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::getBaseUrl()));
			$breadcrumbs->addCrumb('search_results', array('label'=>$tit, 'title'=>$tit, 'link'=>Mage::getUrl(Mage::helper('job')->getSearchUrl())));
		}		
		
		$this->setPost($this->getRequest()->getPost());
		
		$this->getLayout()->getBlock('head')->setTitle($tit); 
		
		
		return parent::_prepareLayout();
	}
	
	protected function _beforeToHtml(){
		if($this->needFetchJobs()){
			$this->_fetchJobs();
			$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
			$pager->setShowPerPage(false);
			$pager->setAvailableLimit(array(12=>12));
			$pager->setPageSize(12);
	        $pager->setCollection($this->getResults());
	        $this->setChild('pager', $pager);
		}
		return parent::_beforeToHtml();
	}
	
	protected function _fetchJobs(){
		$collection = Mage::getModel('job/job')->getCollection();
		/* @var $collection Iconic_Job_Model_Mysql4_Job_Collection */
		
		if ($this->getKeyword()){
			$likeStm = array('like'=> "%{$this->getKeyword()}%");
			$collection->addFieldToFilter(
							array('title', 'info', 'requirement'),
							array($likeStm, $likeStm, $likeStm));
		}
		
		if ($this->getCategory()){
			$collection->addFieldToFilter('category_id', array('eq' => $this->getCategory()));
		}
		
		if ($this->getLocation()){
			$collection->addFieldToFilter('location_id', array('eq' => $this->getLocation()));
		}
		
		if ($this->getLanguage()){
			$collection->addFieldToFilter('language_id', array('like' => '%,'.$this->getLanguage().',%'));
		}
		
		if ($this->getFeature()){
			$collection->addFieldToFilter('feature_id', array('like' => '%,'.$this->getFeature().',%'));
		}
		
		if ($this->getJobLevel()){
			$collection->addFieldToFilter('job_level', array('eq' => $this->getJobLevel()));
		}
		
		if ($this->getFunctionCategory()){
			$collection->addFieldToFilter('function_category_id', array('eq' => $this->getFunctionCategory()));
		}
		
		if($this->getIndustry()){
			$cats = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$this->getIndustry()));
			$catIds = array();
			foreach($cats as $cat){
				$catIds[] = $cat->getCategoryId();
			}
			$collection->addFieldToFilter('category_id', array('in' => $catIds));
		}
		
		if($this->getFunction()){
			$cats2 = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$this->getFunction()));
			$catIds2 = array();
			foreach($cats2 as $cat){
				$catIds2[] = $cat->getCategoryId();
			}
			$collection->addFieldToFilter('function_category_id', array('in' => $catIds2));
		}
		
		if($this->getMultiLocation()){
			$collection->addFieldToFilter('location_id', array('in' => $this->getMultiLocation()));
		}
		if($this->getMultiCategory()){
			$multicat = array();
			foreach($this->getMultiCategory() as $catId){
				$cats = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$catId));
				foreach($cats as $cat){
					$multicat[] = $cat->getCategoryId();
				}
			}
			$collection->addFieldToFilter('category_id', array('in' => $multicat));
		}
		if($this->getMultiFunction()){
			$multifunction = array();
			foreach($this->getMultiFunction() as $catId){
				$cats = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$catId));
				foreach($cats as $cat){
					$multifunction[] = $cat->getCategoryId();
				}
			}
			$collection->addFieldToFilter('function_category_id', array('in' => $multifunction));
		}
		if($this->getMultiLanguage()){
			$condition = array();
			foreach($this->getMultiLanguage() as $lang){
				$condition[] = array('like' => '%,'.$lang.',%');
			}
			$collection->addFieldToFilter('language_id', $condition);
		}
		
		$collection->setOrder('created_time','DESC');
		//var_dump($collection->getSelect()->__toString());
		
		
		$this->setResults($collection);
	}
	
	public function needFetchJobs(){
		return !$this->getResults() and $this->isQueryParamAvailable();
	}
	
	public function isQueryParamAvailable(){
		return true;
	}
	
	public function getFilters(){
		if($this->needFetchJobs()){
			$this->_fetchJobs();
		}
		$filters = array();
		if (!$this->getCategory()){
			$filter = $this->getLayout()->createBlock('job/search_filter');
			$filter->setCollection($this->getResults());
			$filter->setField('category_id');
			$filter->setDesticationField('category_id');
			$filter->setModel('job/category');
			$filter->setParamName('category');
			$filter->setTitle(Mage::helper('job')->__('Tìm việc theo ngành nghề'));
			$filters['category'] = $filter;
		}
		
		if (!$this->getFunctionCategory()){
			$filter = $this->getLayout()->createBlock('job/search_filter');
			$filter->setCollection($this->getResults());
			$filter->setField('function_category_id');
			$filter->setDesticationField('parentcategory_id');
			$filter->setModel('job/parentcategory');
			$filter->setParamName('function_category');
			$filters['function_category'] = $filter;
			$filter->setTitle(Mage::helper('job')->__('Tìm việc theo chức năng'));
		}

		if (!$this->getJobLevel()){
			$filter = $this->getLayout()->createBlock('job/search_filter');
			$filter->setCollection($this->getResults());
			$filter->setField('job_level');
			$filter->setDesticationField('level_id');
			$filter->setModel('job/level');
			$filter->setParamName('level');
			$filter->setTitle(Mage::helper('job')->__('Tìm việc theo đối tượng'));
			$filters['level'] = $filter;
		}
		return $filters;
	}
 
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
}
        