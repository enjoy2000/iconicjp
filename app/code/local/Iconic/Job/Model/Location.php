<?php
 
class Iconic_Job_Model_Location extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('job/location');
    }
	
	public function getUrl(){
		$base = Mage::helper('job')->getBaseUrl();
		$url = Mage::helper('job')->getSearchUrl() . '/' . $this->getUrlKey();
		return $base.$url;
	}
}