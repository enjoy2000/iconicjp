<?php
class Iconic_Job_AjaxController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){		
        $this->loadLayout();  
		$this->renderLayout();
	}
	
	public function confirmAction(){
		$this->loadLayout();  
		$this->renderLayout();
	}
	
	public function testAction(){
		$jobs = Mage::getModel('job/job')->getCollection();
		foreach($jobs as $job){
			$job->setLocationId(','.$job->getLocationId().',')->save();
			echo $job->getTitle() .'<br />';
		}
		echo 'finish';
	}
}
