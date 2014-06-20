<?php
 
class Iconic_Blog_Model_Blog extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('blog/blog');
    }
	
	protected function _beforeSave()
    {
        if(!$this->getUrlKey()){
            $urlKey = Mage::helper('blog')->formatUrlKeyJp($this->getTitle());
            if(!Mage::getModel('blog/blog')->load($urlKey, 'url_key')->getId()){
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
		return $base.Mage::helper('blog')->getBlogUrl().'/'.$this->getUrlKey();
	}

}