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
	
}