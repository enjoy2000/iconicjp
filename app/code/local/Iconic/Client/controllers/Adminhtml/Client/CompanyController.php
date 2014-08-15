<?php
 
class Iconic_Client_Adminhtml_Client_CompanyController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('client/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Company Manager'), Mage::helper('adminhtml')->__('Company Manager'));
        return $this;
    }   
   
    public function indexAction() {
        $this->_initAction(); 
        $this->_addContent($this->getLayout()->createBlock('client/adminhtml_company'));
        $this->renderLayout();
    }
 
    public function editAction()
    {
        $clientId     = $this->getRequest()->getParam('id');
        $customerModel  = Mage::getModel('customer/customer')->load($clientId);
 
        if ($customerModel->getId() || $clientId == 0) {
 
            Mage::register('company_data', $customerModel);
 
            $this->loadLayout();
            $this->_setActiveMenu('client/items');
           
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Company'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Company Details'));
           
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('client/adminhtml_company_edit'))
                 ->_addLeft($this->getLayout()->createBlock('client/adminhtml_company_edit_tabs'));
               
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('client')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
   
    public function newAction()
    {
        $this->_forward('edit');
    }
   
    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $customerModel = Mage::getModel('customer/customer');
               
                $customerModel->setData($postData)
                	->setId($this->getRequest()->getParam('id'))
                    ->save();
               
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setCompanyData(false);
 
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCompanyData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
   
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $customerModel = Mage::getModel('customer/customer');
               
                $customerModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                   
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
	
	/*
	 * MassDelete action for delete many companies at once. 
	*/
	public function massDeleteAction()
    {
        if(is_array($this->getRequest()->getParam('customer'))) {
        	try{
                $customerIds = $this->getRequest()->get('customer');
            	foreach($customerIds as $k => $v){
            	   Mage::getModel('customer/customer')->setId($v)->delete();
            	}
               Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Companies were successfully deleted'));
               $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }
	
    /**
     * Product grid for AJAX company.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('client/adminhtml_company_grid')->toHtml()
        );
    }
	
	/**
     * Export customer grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'customers.csv';
        $content    = $this->getLayout()->createBlock('client/adminhtml_company_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }
}