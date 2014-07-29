<?php
class Iconic_Client_AjaxController extends Mage_Core_Controller_Front_Action{
	
	public function deletejobAction(){
		// redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $session->setBeforeAuthUrl( Mage::getUrl('*/*/*', array('_current' => true)) );
            $this->_redirect('/');
            return $this;
        }
		$json = array('status'=>false,'message'=>Mage::helper('client')->__('There is some error.'));
		
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		if($id = $this->getRequest()->getParam('id')){
			$job = Mage::getModel('job/job')->load($id);
			if($job->getId()){
				if($job->getCustomerId() == $customer->getId()){
					try{
						$job->delete();
						$json['status'] = true;
						$json['message'] = Mage::helper('client')->__('This job was deleted.');
					} catch (Exception $e){
						$json['status'] = false;
						$json['message'] = $e->getMessage();
					}
				}else{
					$json['status'] = false;
					$json['message'] = Mage::helper('client')->__('This job does not belong your account.');
				}
			}else{
				$json['status'] = false;
				$json['message'] = Mage::helper('client')->__('Could not find this job');
			}
		}
		echo json_encode($json);
	}
}
