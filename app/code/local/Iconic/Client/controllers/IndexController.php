<?php
class Iconic_Client_IndexController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
		
		$uploaddir = Mage::getBaseDir().'/files/';
		var_dump($uploaddir);die;
        $this->loadLayout();  
		$this->renderLayout();
	}
	
	public function uploadAction(){
		if(!file_exists(Mage::getBaseDir().'/media/logo/')){
			mkdir(Mage::getBaseDir().'/media/logo/',0777);
		}    
		$maxwidth = 140;
		$maxheight = 100;
		$error = false;
		$files = array();
		$uploaddir = Mage::getBaseDir().'/media/logo/';
		$file = $_FILES['file'];
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
			$newname = date('Ymdhis').rand(1,999).'.'.$ext;
			if(move_uploaded_file($file['tmp_name'], $uploaddir . $newname))
			{
				if(Mage::getSingleton('core/session')->getCompanyLogo()){
					unlink($uploaddir.Mage::getSingleton('core/session')->getCompanyLogo());
				}
				$imageObj = new Varien_Image($uploaddir . $newname);
				$imageObj->constrainOnly(TRUE);
				$imageObj->keepAspectRatio(TRUE);
				$imageObj->keepFrame(FALSE);
				$imageObj->keepTransparency(TRUE);
				$imageObj->resize($maxwidth, $maxheight);
				$imageObj->save($uploaddir . $newname);
				$files[] = $newname;
				Mage::getSingleton('core/session')->setCompanyLogo($newname);
			}
			else
			{
			    $error = true;
			}
			$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
		}else{
			$data = array('error' => 'You have to choose image file.');
		}
		echo json_encode($data);
	}
}
