<?php
class Iconic_Client_JobController extends Mage_Core_Controller_Front_Action{
	
	public function manageAction(){
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $session->setBeforeAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $this->_redirect('/');
            return $this;
        }
        $this->loadLayout();
		$manageJobBlock = $this->getLayout()->getBlock('manage_job');
		if($this->getRequest()->getParam('status')){
			$manageJobBlock->setStatus($this->getRequest()->getParam('status'));
		}
		$jobs = Mage::getModel('job/job')->getCollection();
		
		$this->renderLayout();
	}
	
	public function postAction(){
		if(Mage::app()->getWebsite()->getCode() == 'base'){
    		header("Location: ".Mage::helper('job')->getUrl().'company/');
    		die;
    	}
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
		
		/* Clear job data */
		Mage::getSingleton('customer/session')->setJobData(false);
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($jobId = $this->getRequest()->getParam('id')){
			$job = Mage::getModel('job/job')->load($jobId);
			
			//check job belongs to customer and not actived
			if(($job->getCustomerId() != $customer->getId()) && ($job->getStatus() == 'active')){
				$this->_redirect('/');
				return;
			}
			
			/* Change data format to render to edit page */
			$job = (array) $job->getData();
			$job['feature_id'] = explode(',', substr($job['feature_id'], 1, -1));
			$jobLang = $job['language_id'];
			$jobLang = explode(',', substr($job['language_id'], 1, -1));
			$arrayLang = array();
			foreach($jobLang as $lang){
				list($key, $level) = explode('-',$lang);
				$arrayLang[$key] = $level;
			}
			$job['language_id'] = $arrayLang;
			$jobLocationId = explode(',', substr($job['location_id'], 1, -1));
			$job['location_id'] = $jobLocationId[0];
			Mage::getSingleton('customer/session')->setJobData($job);
		}
		
		if($data = $this->getRequest()->getPost()){
			try{
				$formData = $data;
				$jobModel = Mage::getModel('job/job');
				if(count($data) < 13){
					Mage::getSingleton('core/session')->addError(Mage::helper('client')->__('Not enough information.'));
					Mage::getSingleton('customer/session')->setJobData($formData);
					$this->_redirect('*/*/*', array('id'=>$jobId));
					return;
				}
				//var_dump($data['language_id']);die;
				if(!$data['info'] || !$data['requirement'] || count($data['feature_id']) <= 0 || count($data['language_id']) <= 0 || !$data['title'] || !$data['category_id'] || !$data['function_category_id']){
					Mage::getSingleton('core/session')->addError(Mage::helper('client')->__('Not enough information.'));
					Mage::getSingleton('customer/session')->setJobData($formData);
					$this->_redirect('*/*/*', array('id'=>$jobId));
					return;
				}
				
				if($data['submit'] == 1){
					$data['status'] = 'pending';
				}else{
					$data['status'] = 'draft';
				}
				
				//if edit exist job not change url key
				if($jobId){
					$data['url_key'] = $job['url_key'];
				}
				$data['customer_id'] = $customer->getId();
				/* Created time */
	            $currentDate = Date('Y-m-d H:i:s');
				if($jobId){
					$data['update_time'] = $currentDate;
				}else{
					$data['created_time'] = $currentDate;
				}
				$requirement = strip_tags($_POST['requirement'], '<p><ol><ul><li><em><strong><b><i>');
				$data['requirement'] = $requirement;
				$info = strip_tags($_POST['info'], '<p><ol><ul><li><em><strong><b><i>');
				$data['info'] = $info;
				/* Change data format to save to databse */
				$data['location_id'] = ','.$data['location_id'].',';
				$data['feature_id'] = ','.implode(',', $data['feature_id']).',';
				$langIds = array();
				foreach($data['language_id'] as $k=>$v){
					$langIds[] = $k.'-'.$v;
				}
				$data['language_id'] = ','.implode(',', $langIds).',';
				
				/* Save Job */
				$jobModel->setData($data)
						->setId($jobId)
						->save();
				if($data['status'] == 'draft'){
					$successMessage = Mage::helper('client')->__('Your job has been saved in draft.');
				}else{
					$successMessage = Mage::helper('client')->__('Please wait our moderators approve your job.');
				}
				Mage::getSingleton('core/session')->addSuccess($successMessage);
				Mage::getSingleton('customer/session')->setJobData(false);
				$this->_redirect('client/job/manage', array('status' => $data['status']));
				return;
			}catch(Exception $e){
				Mage::getSingleton('core/session')->addError($e->getMessage());
				Mage::getSingleton('customer/session')->setJobData($formData);
				$this->_redirect('*/*/*', array('id'=>$jobId));
				return;
			}
		}
		
		$this->renderLayout();
	}
}
