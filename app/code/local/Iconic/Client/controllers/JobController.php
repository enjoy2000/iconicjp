<?php
class Iconic_Client_JobController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
        $this->loadLayout();
		
		
		
		$this->renderLayout();
	}
	
	public function postAction(){
		$this->loadLayout();
		
		if($data = $this->getRequest()->getPost()){
			$jobModel = Mage::getModel('job/job');
			$data['status'] = 'pending';
			/* Created time */
            $currentDate = Date('Y-m-d H:i:s');
			$data['created_time'] = $currentDate;
		}
		
		$this->renderLayout();
	}
}
