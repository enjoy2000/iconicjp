<?php
class Iconic_Blog_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getRoute(){
		return 'blog';
	}
	
	public function getTransName($obj){
		$storeCode = Mage::app()->getStore()->getCode();
		if($storeCode == 'jp'){
			return $obj->getName();
		}else{
			return $obj->getNameEn();
		}
	}
	
	public function imgHeight(){
		return 125;
	}
	
	public function imgWidth(){
		return 162;
	}
	
	public function formatDate($date)
    {
        $format = date('Y年m月d日', strtotime($date));
        return $format;
    }
}