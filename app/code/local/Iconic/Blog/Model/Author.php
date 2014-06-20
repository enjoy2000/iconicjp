<?php
 
class Iconic_Blog_Model_Author extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/author');
    }
	
	protected function _beforeSave()
    {
        if(!$this->getUrlKey()){
            $urlKey = Mage::helper('blog')->formatUrlKey($this->getNameEn());
            if(!Mage::getModel('blog/author')->load($urlKey, 'url_key')->getId()){
                $this->setUrlKey($urlKey);
            } else {
                $urlKey .= '-' . $this->getId();
            	$this->setUrlKey($urlKey);
            }
        }
        parent::_beforeSave();
    }
	
	public function getUrl(){
		$base = Mage::helper('blog')->getBaseUrl();
		return $base.Mage::helper('blog')->getSearchUrl().'/'.$this->getUrlKey();
	}

}