<?php
class Iconic_Job_SuccessController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){		
        $this->loadLayout();  
		$this->renderLayout();
	}
	
	public function confirmAction(){
		$this->loadLayout();  
		$this->renderLayout();
	}
	
	public function requestAction(){
		$this->loadLayout();  
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('create_cv', array('label'=>$helper->__('求人依頼申込'), $helper->__('求人依頼申込')));
		}
		$this->getLayout()->getBlock('head')->setTitle($helper->__('求人依頼申込'));
		$this->renderLayout();
	}
	
	public function forgotpassAction(){
		Mage::getSingleton('customer/session')->setShowForgotPassword(1);
		$this->_redirect('/');
		return;
	}
	
}
