<?php
class Iconic_Client_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function isEmployerSite(){
		$websiteCode = Mage::app()->getWebsite()->getCode();
		return $websiteCode != 'base';
	}
	
	public function isJapanese(){
		$localeCode = Mage::app()->getLocale()->getLocaleCode();
		return $localeCode == 'ja_JP';
	}
}