<?php
class Iconic_Client_Block_Manage extends Mage_Core_Block_Template
{
	protected function _prepareLayout(){
		parent::_prepareLayout();
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('client')->__('投稿を管理する'));
	}
	
	protected function _beforeToHtml(){
		if($this->needfetchJobs()){
			$this->_fetchJobs();
			$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
			$pager->setShowPerPage(false);
			$pager->setShowAmounts(false);
			$pager->setAvailableLimit(array(8=>8));
			$pager->setPageSize(8);
	        $pager->setCollection($this->getResults());
	        $this->setChild('pager', $pager);
		}
		parent::_beforeToHtml();
	}
	
	protected function _fetchJobs(){
		$collection = Mage::getModel('job/job')->getCollection();
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$collection->addFieldToFilter('customer_id', array('eq'=>$customer->getId()));
		if($this->getParam()){
			$collection->addFieldToFilter('status', array('like'=>$this->getParam()));
		}
		$collection->setOrder('update_time','DESC');
		$this->setResults($collection);
	}
	
	public function getParam(){
		return $this->getStatus();
	}
	
	public function needfetchJobs(){
		return !$this->getResults() and Mage::getSingleton('customer/session')->isLoggedIn();
	}
 
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
	
	public function getCount($status){
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$collection = Mage::getModel('job/job')->getCollection()
							->addFieldToFilter('customer_id', array('eq'=>$customer->getId()));
		if($status){
			$collection->addFieldToFilter('status', array('eq'=>$status));
		}
		return $collection->count();
	}
	
	public function getUrl($status){
		if($status){
			$url = Mage::getUrl('client/job/manage', array('status'=>$status));
		}else{
			$url = Mage::getUrl('client/job/manage');
		}
		return $url;
	}
}
