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
}
