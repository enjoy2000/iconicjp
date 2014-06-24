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
	
	public function getShareCount($url){
		$count = array();
		$url = rawurldecode($url);
		//google
	    $html =  file_get_contents( "https://plusone.google.com/_/+1/fastbutton?url=".$url);
	    $doc = new DOMDocument();   $doc->loadHTML($html);
	    $counter=$doc->getElementById('aggregateCount');
	    $count['google'] = $counter->nodeValue;
		//facebook
		$xml = file_get_contents("http://api.facebook.com/restserver.php?method=links.getStats&urls=".$url);
	    $xml = simplexml_load_string($xml);
	    $count['facebook'] = json_decode($xml->link_stat->like_count);
		//twitter
		$json = file_get_contents( "http://urls.api.twitter.com/1/urls/count.json?url=".$url );
	    $ajsn = json_decode($json, true);
	    $count['twitter'] = $ajsn['count'];
		//LinkedIn
		/*
 		$stream = file_get_contents("http://www.linkedin.com/countserv/count/share?url={$url}&for‌​mat=json");
		$json = json_decode($stream, true);
		$count['linkedin'] = intval($json['count']);
		*/
		
		return $count;
	}
	
}
		