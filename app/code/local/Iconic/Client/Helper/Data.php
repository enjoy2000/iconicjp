<?php
class Iconic_Client_Helper_Data extends Mage_Core_Helper_Abstract
{
	/* Check the site is for Employer or not */
	public function isEmployerSite(){
		$websiteCode = Mage::app()->getWebsite()->getCode();
		return $websiteCode != 'base';
	}
	
	/* Check locale is Japan or not */
	public function isJapanese(){
		$localeCode = Mage::app()->getLocale()->getLocaleCode();
		return $localeCode == 'ja_JP';
	}
}