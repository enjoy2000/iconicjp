<?php
class Iconic_Job_DetailsController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){		
        $this->loadLayout();  
		//redirect if user not login 
		if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $session = Mage::getSingleton('customer/session');
			Mage::getSingleton('customer/session')->setShowLogin(1);
            $session->setAfterAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $session->setBeforeAuthUrl( Mage::helper('core/url')->getCurrentUrl() );
            $this->_redirect('/');
            return $this;
        }	 
		
		$id = (int) $this->getRequest()->get('id');
		if($id <=0){
			Mage::helper('job')->redirectToSearchPage();
		}
		
		$item = Mage::getModel('job/job')->load($id);
		if(!$item->getId()){
			Mage::helper('job')->redirectToSearchPage();
		}
		$cat = Mage::getModel('job/category')->load($item->getCategoryId());
		$parent = Mage::getModel('job/parentcategory')->load($cat->getParentcategoryId());
		
		//set breadcrumbs		
		$helper = Mage::helper('job');
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label'=>$helper->__('ホーム'), 'title'=>$helper->__('ホーム'), 'link'=>Mage::helper('job')->getBaseUrl()));
			$breadcrumbs->addCrumb('search_results', array('label'=>$helper->getTransName($parent), 'title'=>$helper->getTransName($parent), 'link'=>$parent->getUrl()));
			$breadcrumbs->addCrumb('job_details', array('label'=>$item->getTitle(), $item->getTitle()));
		}	
		//set title by job title
		$this->getLayout()->getBlock('head')->setTitle($item->getTitle()); 
		//set description
		$desc = $item->getTitle();
		$desc .= ' - ' . Mage::helper('job')->limitText(strip_tags($item->getInfo()), 160 - strlen($item->getTitle) - 6);
		$this->getLayout()->getBlock('head')->setDescription($desc);
		//set item to block
		$this->getLayout()->getBlock('job_details')->setItem($item);
		//set other varibles from other models			
		$this->getLayout()->getBlock('job_details')->setCategory(Mage::getModel('job/category')->load($item->getCategoryId()));
		$this->getLayout()->getBlock('job_details')->setFunctionCategory(Mage::getModel('job/category')->load($item->getFunctionCategoryId()));
		$this->getLayout()->getBlock('job_details')->setLocation(Mage::getModel('job/location')->load($item->getLocationId()));
		$this->getLayout()->getBlock('job_details')->setLevel(Mage::getModel('job/level')->load($item->getJobLevel()));
		$this->getLayout()->getBlock('job_details')->setType(Mage::getModel('job/type')->load($item->getJobType()));
		
		//get jobs form same category
		$this->getLayout()->getBlock('job_details')->setJobsInCategory($item->getJobsInCategory());
		       
		$this->renderLayout();
		   
	}
}
