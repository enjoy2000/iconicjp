<?php
class Iconic_Client_JobController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
        $this->loadLayout();
		
		
		
		$this->renderLayout();
	}
	
	public function postAction(){
		$this->loadLayout();
		
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $session->setBeforeAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $this->_redirect('/');
            return $this;
        }
		
		if($data = $this->getRequest()->getPost()){
			try{
				$customer = Mage::getSingleton('customer/session')->getCustomer();
				$jobModel = Mage::getModel('job/job');
				if(count($data) < 13){
					Mage::getSingleton('core/session')->addError(Mage::helper('client')->__('Not enough information.'));
					Mage::getSingleton('customer/session')->setJobData($data);
					$this->_redirect('*/*/*');
					return;
				}
				if(!$data['info'] || !$data['requirement'] || !$data['feature_id']){
					Mage::getSingleton('core/session')->addError(Mage::helper('client')->__('Not enough information.'));
					Mage::getSingleton('customer/session')->setJobData($data);
					$this->_redirect('*/*/*');
					return;
				}
				foreach($data as $k=>$v){
					$data[$k] = mysql_real_escape_string($v);
				}
				if($data['submit'] == 1){
					$data['status'] = 'pending';
				}else{
					$data['status'] = 'draft';
				}
				$data['customer_id'] = $customer->getId();
				/* Created time */
	            $currentDate = Date('Y-m-d H:i:s');
				$data['created_time'] = $currentDate;
				$requirement = strip_tags($_POST['requirement'], '<p><ol><ul><li><em><strong><b><i>');
				$data['requirement'] = $requirement;
				$info = strip_tags($_POST['info'], '<p><ol><ul><li><em><strong><b><i>');
				$data['info'] = $info;
				$jobModel->setData($data)->save();
				Mage::getSingleton('core/session')->addSuccess(Mage::helper('client')->__('Please wait our moderators approve your job.'));
				Mage::getSingleton('customer/session')->setJobData(false);
				$this->_redirect('/');
				return;
			}catch(Exception $e){
				Mage::getSingleton('core/session')->addError($e->getMessage());
				Mage::getSingleton('customer/session')->setJobData($data);
				$this->_redirect('*/*/*', array('id'=>$this->getRequest()->getParam('id')));
				return;
			}
		}
		
		$this->renderLayout();
	}
}
