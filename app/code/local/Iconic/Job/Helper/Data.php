<?php

class Iconic_Job_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function formatUrlKey($str)
    {
        $trans = array(
		'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
		'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a', 'ă' => 'a',
		'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a', 'â' => 'a',
		'Á' => 'a', 'À' => 'a', 'Ả' => 'a', 'Ã' => 'a', 'Ạ' => 'a',
		'Ắ' => 'a', 'Ằ' => 'a', 'Ẳ' => 'a', 'Ẵ' => 'a', 'Ặ' => 'a', 'Ă' => 'a',
		'Ấ' => 'a', 'Ầ' => 'a', 'Ẩ' => 'a', 'Ẫ' => 'a', 'Ậ' => 'a', 'Â' => 'a',
		'Đ' => 'd', 'đ' => 'd',
		'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
		'É' => 'e', 'È' => 'e', 'Ẻ' => 'e', 'Ẽ' => 'e', 'Ẹ' => 'e',
		'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e', 'ê' => 'e',
		'Ế' => 'e', 'Ề' => 'e', 'Ể' => 'e', 'Ễ' => 'e', 'Ệ' => 'e', 'Ê' => 'e',
		'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
		'Í' => 'i', 'Ì' => 'i', 'Ỉ' => 'i', 'Ĩ' => 'i', 'Ị' => 'i',
		'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
		'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
		'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'ơ' => 'o',
		'Ó' => 'o', 'Ò' => 'o', 'Ỏ' => 'o', 'Õ' => 'o', 'Ọ' => 'o',
		'Ố' => 'o', 'Ồ' => 'o', 'Ổ' => 'o', 'Ỗ' => 'o', 'Ộ' => 'o',
		'Ớ' => 'o', 'Ờ' => 'o', 'Ở' => 'o', 'Ỡ' => 'o', 'Ợ' => 'o', 'Ơ' => 'o',
		'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
		'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'ư' => 'u',
		'Ú' => 'u', 'Ù' => 'u', 'Ủ' => 'u', 'Ũ' => 'u', 'Ụ' => 'u',
		'Ứ' => 'u', 'Ừ' => 'u', 'Ử' => 'u', 'Ữ' => 'u', 'Ự' => 'u', 'Ư' => 'u',
		'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
		'Ý' => 'y', 'Ỳ' => 'y', 'Ỷ' => 'y', 'Ỹ' => 'y', 'Ỵ' => 'y'
		);
		$str = strtr($str, $trans);
		
		$urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($str));
		$urlKey = strtolower($urlKey);
		$urlKey = trim($urlKey, '-');
    
        return $urlKey;
    }

	public function _toSlugTransliterate($string) {
	    // Lowercase equivalents found at:
	    // https://github.com/kohana/core/blob/3.3/master/utf8/transliterate_to_ascii.php
	    $lower = array(
	        'à'=>'a','ô'=>'o','d'=>'d','?'=>'f','ë'=>'e','š'=>'s','o'=>'o',
	        'ß'=>'ss','a'=>'a','r'=>'r','?'=>'t','n'=>'n','a'=>'a','k'=>'k',
	        's'=>'s','?'=>'y','n'=>'n','l'=>'l','h'=>'h','?'=>'p','ó'=>'o',
	        'ú'=>'u','e'=>'e','é'=>'e','ç'=>'c','?'=>'w','c'=>'c','õ'=>'o',
	        '?'=>'s','ø'=>'o','g'=>'g','t'=>'t','?'=>'s','e'=>'e','c'=>'c',
	        's'=>'s','î'=>'i','u'=>'u','c'=>'c','e'=>'e','w'=>'w','?'=>'t',
	        'u'=>'u','c'=>'c','ö'=>'o','è'=>'e','y'=>'y','a'=>'a','l'=>'l',
	        'u'=>'u','u'=>'u','s'=>'s','g'=>'g','l'=>'l','ƒ'=>'f','ž'=>'z',
	        '?'=>'w','?'=>'b','å'=>'a','ì'=>'i','ï'=>'i','?'=>'d','t'=>'t',
	        'r'=>'r','ä'=>'a','í'=>'i','r'=>'r','ê'=>'e','ü'=>'u','ò'=>'o',
	        'e'=>'e','ñ'=>'n','n'=>'n','h'=>'h','g'=>'g','d'=>'d','j'=>'j',
	        'ÿ'=>'y','u'=>'u','u'=>'u','u'=>'u','t'=>'t','ý'=>'y','o'=>'o',
	        'â'=>'a','l'=>'l','?'=>'w','z'=>'z','i'=>'i','ã'=>'a','g'=>'g',
	        '?'=>'m','o'=>'o','i'=>'i','ù'=>'u','i'=>'i','z'=>'z','á'=>'a',
	        'û'=>'u','þ'=>'th','ð'=>'dh','æ'=>'ae','µ'=>'u','e'=>'e','i'=>'i',
	    );
	    return str_replace(array_keys($lower), array_values($lower), $string);
	}
	
	public function formatUrlKeyJp($string, $separator = '-') {
	    // Work around this...
	    #$string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
	    $string = $this->_toSlugTransliterate($string);
	
	    // Remove unwanted chars + trim excess whitespace
	    // I got the character ranges from the following URL:
	    // https://stackoverflow.com/questions/6787716/regular-expression-for-japanese-characters#10508813
	    $regex = '/[^一-龠ぁ-ゔァ-ヴーａ-ｚＡ-Ｚ０-９a-zA-Z0-9々〆〤.+ -]|^\s+|\s+$/u';
	    $string = preg_replace($regex, '', $string);
	
	    // Using the mb_* version seems safer for some reason
	    $string = mb_strtolower($string);
	
	    // Same as before
	    $string = preg_replace("/[ {$separator}]+/", $separator, $string);
	    return $string;
	}
	
	public function noAccent($str)
    {
        $trans = array(
		'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
		'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a', 'ă' => 'a',
		'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a', 'â' => 'a',
		'Á' => 'a', 'À' => 'a', 'Ả' => 'a', 'Ã' => 'a', 'Ạ' => 'a',
		'Ắ' => 'a', 'Ằ' => 'a', 'Ẳ' => 'a', 'Ẵ' => 'a', 'Ặ' => 'a', 'Ă' => 'a',
		'Ấ' => 'a', 'Ầ' => 'a', 'Ẩ' => 'a', 'Ẫ' => 'a', 'Ậ' => 'a', 'Â' => 'a',
		'Đ' => 'd', 'đ' => 'd',
		'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
		'É' => 'e', 'È' => 'e', 'Ẻ' => 'e', 'Ẽ' => 'e', 'Ẹ' => 'e',
		'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e', 'ê' => 'e',
		'Ế' => 'e', 'Ề' => 'e', 'Ể' => 'e', 'Ễ' => 'e', 'Ệ' => 'e', 'Ê' => 'e',
		'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
		'Í' => 'i', 'Ì' => 'i', 'Ỉ' => 'i', 'Ĩ' => 'i', 'Ị' => 'i',
		'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
		'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
		'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'ơ' => 'o',
		'Ó' => 'o', 'Ò' => 'o', 'Ỏ' => 'o', 'Õ' => 'o', 'Ọ' => 'o',
		'Ố' => 'o', 'Ồ' => 'o', 'Ổ' => 'o', 'Ỗ' => 'o', 'Ộ' => 'o',
		'Ớ' => 'o', 'Ờ' => 'o', 'Ở' => 'o', 'Ỡ' => 'o', 'Ợ' => 'o', 'Ơ' => 'o',
		'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
		'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'ư' => 'u',
		'Ú' => 'u', 'Ù' => 'u', 'Ủ' => 'u', 'Ũ' => 'u', 'Ụ' => 'u',
		'Ứ' => 'u', 'Ừ' => 'u', 'Ử' => 'u', 'Ữ' => 'u', 'Ự' => 'u', 'Ư' => 'u',
		'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
		'Ý' => 'y', 'Ỳ' => 'y', 'Ỷ' => 'y', 'Ỹ' => 'y', 'Ỵ' => 'y'
		);
		$str = strtr($str, $trans);
		return $str;
	}
	
	public function formatDate($date)
    {
        $format = date('Y/m/d', strtotime($date));
        return $format;
    }
	
	public function getCurrency(){
		return Mage::helper('job')->__('VND');
	}

	public function string_limit_words($string,$number=7){
		$string = strip_tags($string);
		$words = explode(' ', $string, ($number + 1));
		if(count($words) > $number){
			array_pop($words);
			return implode(' ', $words).'...';		
		}else{
			return implode(' ', $words);
		}		
	}
	
	public function getJobLink($job){
		$link = Mage::getBaseUrl()
					. $job->getCategory()->getParentCategory()->getUrlKey()
					. '/'
					. $job->getCategory()->getUrlKey()
					. '/'
					. $job->getUrlKey()
					. Mage::helper('clnews')->getNewsitemUrlSuffix();
		return $link;
	}
	
	public function getApplyLink($jobId){
		return Mage::getBaseUrl().'job/apply?id='.$jobId;
	}
	
	public function renderJob($job){
		$location = Mage::getModel('job/location')->load($job->getLocationId())->getName();
		
		$render = '<a href="'.$this->getJobLink($job).'" title="' . $job->getTitle().'">' . $this->string_limit_words($job->getTitle()).'</a>';
		$render .= '<span class="inline created-time">'.$this->formatDate($job->getCreatedTime()).'</span>';
		$render .= '<span class="inline location">'.$location.'</span>';
		
		return $render;
	}

	public function highlight($inp, $words){
		$replace=array_flip(array_flip($words)); // remove duplicates
		$pattern=array();
		foreach ($replace as $k=>$fword) {
			$pattern[]='/\b(' . $fword . ')(?!>)\b/i';
			$replace[$k]='<b>$1</b>';
		}
		return preg_replace($pattern, $replace, $inp);
	}
	
	public function renderTitle($job){
		$title = $this->__('No. %s <span>|</span> %s', $job->getIconicId(), $job->getTitle());
		return $title;
	}
	
	public function redirectToSearchPage(){		
		Mage::app()->getResponse()->setRedirect('/job/search');
		return;
	}

	public function getRoute(){
		return 'job';
	}
	
	public function getCategoryUrl($catId){
		$cat = Mage::getModel('job/category')->load($catId);
		$parent = Mage::getModel('job/parentcategory')->load($cat->getParentcategoryId());
		$url = Mage::getBaseUrl().$parent->getUrlKey().'/'.$cat->getUrlKey();
		
		return $url;
	}
	
	public function getRequestUrl(){
		return 'request-recruitment';
	}
	
	public function getRegisterUrl(){
		return 'register';
	}
	
	public function getSearchUrl(){
		return 'search-job';
	}
	
	public function getForgotUrl(){
		return '';
	}
	
	public function getCreateCVUrl(){
		return 'submit-cv';
	}

	public function getSitemapUrl(){
		return 'site-map';
	}
	
	public function getContactUrl(){
		return 'contact';
	}
	
	public function limitText($str, $limit=58){
		if(strlen($str) > $limit){
			// truncate string
		    $stringCut = substr($str, 0, $limit);
		
		    // make sure it ends in a word so assassinate doesn't become ass...
		    $str = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
		}
		return $str;
	}
	
	public function writeExcel($fileName, $arrData){
		//Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		//$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader = new PHPExcel_Reader_Excel2007();
		$objPHPExcel = $objReader->load(Mage::getBaseDir()."/templates/register_form.xlsx");
		// Set properties
		$objPHPExcel->getProperties()->setCreator("ICONIC Manager");
		$objPHPExcel->getProperties()->setLastModifiedBy("ICONIC Manager");
		$objPHPExcel->getProperties()->setTitle("ICONIC Form");
		$objPHPExcel->getProperties()->setSubject("Employment And Register");
		$objPHPExcel->getProperties()->setDescription("Employment And Register");
		$objPHPExcel->setActiveSheetIndex(0);
		//Write Data
		for($i=0;$i<count($arrData);$i++){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,2,$arrData[$i]);
			//$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,3)->getAlignment()->setWrapText(true);
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($fileName);
	}

	public function getTransName($obj){
		$storeCode = Mage::app()->getStore()->getCode();
		if($storeCode == 'jp'){
			return $obj->getName();
		}else{
			return $obj->getNameEn();
		}
	}
	
	public function getBaseUrl(){
		$storeCode = Mage::app()->getStore()->getCode();
		if($storeCode == 'jp'){
			return Mage::getBaseUrl();
		}else{
			return Mage::getBaseUrl().'en/';
		}
	}
	
	public function checkLogin(){
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
			$session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $session->setBeforeAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $app = Mage::app()
                ->getResponse()
                ->setRedirect('/');
            return $app;
        }else{
        	return;
        }
	}

	public function getPic(){
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($customer->getPic()){
			return $customer->getPic();
		}else{
			$lastpic = Mage::getModel('job/pic')->getCollection()->addFieldToFilter('last_pic', array('eq'=>'yes'))->getFirstItem();
			if(!$lastpic->getId()){
				$pic = Mage::getModel('job/pic')->getCollection()->getFirstItem();
				$pic->setLastPic('yes')->save();
				$customer->setPic($pic->getName())->save();
				return $pic->getName();
			}else{
				$pic = Mage::getModel('job/pic')->getCollection()->addFieldToFilter('pic_id', array('gt'=>$lastpic->getId()))->getFirstItem();
				if(!$pic->getId()){
					$pic = Mage::getModel('job/pic')->getCollection()->getFirstItem();
				}
				$lastpic->setLastPic(NULL)->save();
				$pic->setCurrentInterval($pic->getCurrentInterval() + 1)->save();
				if($pic->getCurrentInterval() ==  $pic->getInterval()){
					$pic->setLastPic('yes')->save();
					$pic->setCurrentInterval(0)->save();
					$customer->setPic($pic->getName())->save();
					return $pic->getName();
				}
				while($pic->getCurrentInterval() !=  $pic->getInterval()){
					$pic = Mage::getModel('job/pic')->getCollection()->addFieldToFilter('pic_id', array('gt'=>$pic->getId()))->getFirstItem();
					if(!$pic->getId()){
						$pic = Mage::getModel('job/pic')->getCollection()->getFirstItem();
					}
					$pic->setCurrentInterval($pic->getCurrentInterval() + 1)->save();
					if($pic->getCurrentInterval() ==  $pic->getInterval()){
						$pic->setLastPic('yes')->save();
						$pic->setCurrentInterval(0)->save();
						$customer->setPic($pic->getName())->save();
						return $pic->getName();
					}
				}
			}
		}
	}

	public function getMailConfig(){
		$config = array(
	                    'auth' => 'login',
	                    'ssl'  => 'tls',
					    'port' =>  587,
					    'username' => 'info@iconic-jp.com',
					    'password' => 'bsc393939'
						);
		$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
		return $transport;
	}
}
	