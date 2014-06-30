<?php
 
class Iconic_Job_Model_Language extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('job/language');
    }


    protected function _beforeSave()
    {
        if(!$this->getUrlKey()){
            $urlKey = Mage::helper('job')->formatUrlKey($this->getNameEn());
            if(!Mage::getModel('job/language')->load($urlKey, 'url_key')->getId()){
                $this->setUrlKey($urlKey);
            } else {
                $urlKey .= '-' . $this->getId();
            	$this->setUrlKey($urlKey);
            }
        }
        parent::_beforeSave();
    }
	
	protected function _afterSave(){
		/*
		if($this->getUrlKey()){
			//check url key
			$count = Mage::getModel('job/category')->getCollection()->addFieldToFilter('category_id',array('neq'=>$this->getId()));
			$count->addFieldToFilter('url_key',array('eq'=>$this->getUrlKey()));
			$count->getCollection()->count();
			if($count > 0){
				$urlkey = $this->getUrlKey() . '-' . $this->getId();
				$this->setUrlKey($urlkey)->save();
			}
		}
		 * 
		 */						
		parent::_afterSave();

	}
	
	public function getUrl(){
		return Mage::helper('job')->getBaseUrl() . Mage::helper('job')->getSearchUrl() . '/' . $this->getUrlKey();
	}
	
	public function getCount(){
		$count = Mage::getModel('job/job')->getCollection()
					->addFieldToFilter('language_id', array('like'=>'%,'.$this->getId().'-1,%'))
					->count();
		return $count;
	}
}