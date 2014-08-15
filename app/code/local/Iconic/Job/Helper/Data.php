<?php

class Iconic_Job_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getUrl(){
		$url = Mage::app()->getStore(1)->getBaseUrl();
		return $url;
	}
	
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
	
	/* Limit string by words count */
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
	
	/* Get job Url */
	public function getJobLink($job){
		$link = Mage::getBaseUrl()
					. $job->getCategory()->getParentCategory()->getUrlKey()
					. '/'
					. $job->getCategory()->getUrlKey()
					. '/'
					. $job->getUrlKey();
		return $link;
	}
	
	/* Get apply link base on job ID */
	public function getApplyLink($jobId){
		return Mage::getBaseUrl().'job/apply?id='.$jobId;
	}
	
	/* Render job HTML */
	public function renderJob($job){
		?>
		<div class="job clearfix">
			<div class="job-wrapper">
				<?php if($job->getFeatureId()): ?>
				<div class="feature-tags clearfix">
					<img class="fll" alt="" src="<?php echo Mage::getBaseUrl() ?>skin/frontend/default/iconic/images/tag-icon.png" />
					<div class="fll">
						<?php foreach(explode(',', substr($job->getFeatureId(),1,-1)) as $featureId): ?>
							<?php $feature = Mage::getModel('job/feature')->load($featureId) ?>
							<a class="tag" href="<?php echo $feature->getUrl() ?>" title="<?php echo Mage::helper('job')->getTransName($feature) ?>">
								<?php echo Mage::helper('job')->getTransName($feature) ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
				<p class="iconic-id">
					<?php echo $this->__('<b>No. %s</b>   |   登録日: %s', $job->getIconicId(), Mage::helper('job')->formatDate($job->getCreatedTime())) ?>
				</p>
				<p class="title">
						<?php echo $job->getTitle() ?>
				</p>
				<div class="job-details">
					<table>
						<tr>
							<td class="tit"><?php echo $this->__('国') ?>:</td><td><?php echo $job->getCountry() ?></td>
							<td class="tit"><?php echo $this->__('勤務地') ?>:</td><td><?php echo $job->getWorkLocation() ?></td>
							<td class="tit"><?php echo $this->__('言語') ?>:</td><td><?php echo $job->getLanguage() ?></td>
							<td class="tit"><?php echo $this->__('給与') ?>:</td><td><?php echo $job->getFullSalary() ?></td>
						</tr>
						<tr>
							<td class="tit"><?php echo $this->__('業種') ?>:</td><td><?php echo $job->getCategoryName() ?></td>
							<td class="tit"><?php echo $this->__('職種') ?>:</td><td><?php echo $job->getFunctionName() ?></td>
							<td class="tit"><?php echo $this->__('レベル ') ?>:</td><td><?php echo $job->getLevel() ?></td>
							<td class="tit"><?php echo $this->__('採用定数') ?>:</td><td><?php echo $job->getAmount() ?></td>
						</tr>
					</table>
				</div>
				<div class="actions">
					<a class="view" href="<?php echo $job->getUrl() ?>"><?php echo $this->__('詳細を見る') ?></a>
					<a class="apply" href="<?php echo $job->getApplyUrl() ?>"><?php echo $this->__('応募する') ?></a>
				</div>
			</div>
		</div>
		<?php
	}
	
	/* Hightlight string that match words */
	public function highlight($inp, $words){
		$replace=array_flip(array_flip($words)); // remove duplicates
		$pattern=array();
		foreach ($replace as $k=>$fword) {
			$pattern[]='/\b(' . $fword . ')(?!>)\b/i';
			$replace[$k]='<b>$1</b>';
		}
		return preg_replace($pattern, $replace, $inp);
	}
	
	/* Render title of job */
	public function renderTitle($job){
		$title = $this->__('No. %s <span>|</span> %s', $job->getIconicId(), $job->getTitle());
		return $title;
	}
	
	/* Redirect to search page */
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
	
	/* Limit string by strlen with limit and add ... */
	public function limitText($str, $limit=58){
		if(strlen($str) > $limit){
			// truncate string
		    $stringCut = substr($str, 0, $limit);
		
		    // make sure it ends in a word so assassinate doesn't become ass...
		    $str = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
		}
		return $str;
	}
	
	/* Write excel file for submit-cv function */
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
	
	/* Check locale and get name in Japanese or English */
	public function getTransName($obj){
		$storeCode = Mage::app()->getStore()->getCode();
		if($storeCode == 'jp' || $storeCode == 'cpjp'){
			return $obj->getName();
		}else{
			return $obj->getNameEn();
		}
	}
	
	/* Get base url of website base on store view */
	public function getBaseUrl(){
		return Mage::getBaseUrl();
	}
	
	/* Check user or login or not then redirect to homepage */
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
	
	/* Auto get next PIC to set for each user */
	public function getPic(){
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$location = $customer->getLocation();
		$piclist = Mage::getModel('job/pic')->getCollection();
		$model = Mage::getModel('job/pic');
		$null = new Zend_Db_Expr("null");
		if($location == 12){ //vietnam
			$collection = Mage::getModel('job/pic')->getCollection()->addFieldToFilter('location', array('eq'=>1))->load();
			$col2 = clone $collection;
			$pic = $model->load('yes', 'last_pic_vn');
			$pic->setLastPicVn($null)->save();
		}else if($location == 5){ //indo
			$collection = Mage::getModel('job/pic')->getCollection()->addFieldToFilter('location', array('eq'=>2))->load();
			$col2 = clone $collection;
			$pic = $model->load('yes', 'last_pic_id');
			$pic->setLastPicId($null)->save();
		}else{
			$collection = Mage::getModel('job/pic')->getCollection()->load();
			$col2 = clone $collection;
			$pic = $model->load('yes', 'last_pic');
			$pic->setLastPic($null)->save();
		}
		//var_dump($pic);die;
		$pic = $collection->clear()->addFieldToFilter('pic_id', array('gt'=>$pic->getId()))->getFirstItem();
		//var_dump($pic);die;
		if(!$pic->hasData()){
			$pic = $col2->getFirstItem();
		}
		// Run loop until find next PIC 
		while($pic->getCurrentInterval() !=  $pic->getInterval() - 1){
			$pic->setCurrentInterval($pic->getCurrentInterval() + 1)->save();
			$pic = $collection->clear()->addFieldToFilter('pic_id', array('gt'=>$pic->getPicId()))->getFirstItem();
			if(!$pic->hasData()){
				$pic = $col2->getFirstItem();
			}
		}
		
		// When file PIC reset interval and set as last PIC return function
		if($pic->getCurrentInterval() ==  $pic->getInterval() - 1){
			$pic->setCurrentInterval(0)->save();
			if($location == 12){
				$pic->setLastPicVn('yes')->save();
			}else if($location == 5){
				$pic->setLastPicId('yes')->save();
			}else{
				$pic->setLastPic('yes')->save();
			}
			return $pic->getName();
		}
	}
	
	/* Mail config for sending with SMTP */
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
	