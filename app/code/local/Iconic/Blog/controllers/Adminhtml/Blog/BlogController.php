<?php
 
class Iconic_Blog_Adminhtml_Blog_BlogController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('blog/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }   
   
    public function indexAction() {
        $this->_initAction();    
        $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_blog'));
        $this->renderLayout();
    }
    public function editAction()
    {
        $blogId     = $this->getRequest()->getParam('id');
        $blogModel  = Mage::getModel('blog/blog')->load($blogId);
 
        if ($blogModel->getId() || $blogId == 0) {
 
            Mage::register('blog_data', $blogModel);
 
            $this->loadLayout();
            $this->_setActiveMenu('blog/items');
           
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
           
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_blog_edit'))
                 ->_addLeft($this->getLayout()->createBlock('blog/adminhtml_blog_edit_tabs'));
               
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Item does not exist'));
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
                $blogModel = Mage::getModel('blog/blog');
                $currentDate = Date('Y-m-d H:i:s');
                $blogModel->setData($postData)
	                     ->setId($this->getRequest()->getParam('id'))
						 ->setUpdateTime($currentDate)
						 ->setLanguageId(','.implode(',', $this->getRequest()->getParam('language_id')).',')
					     ->setAuthorId(','.implode(',', $this->getRequest()->getParam('author_id')).',');
						 
                if(!$this->getRequest()->getParam('id')){
                	$blogModel->setCreatedTime($currentDate);
                }
				$blogModel->save();
				
				if(!$this->getRequest()->getParam('id')){
                	$blogModel->setCreatedTime($currentDate);
					$newblog = Mage::getModel('blog/blog')->getCollection()->getLastItem();
                }else{
                	$newblog = Mage::getModel('blog/blog')->load($this->getRequest()->getParam('id'));
                }
				//$newblog->setLanguageId(','.implode(',', $this->getRequest()->getParam('language_id')).',')
				//	   ->setAuthorId(','.implode(',', $this->getRequest()->getParam('author_id')).',')
				//	   ->save();
				//set url key
				//if($postData['url_key']){
					//$urlkey = Mage::helper('blog')->formatUrlKey($postData['url_key']);
				//}else{
					//$urlkey = Mage::helper('blog')->formatUrlKey($postData['title']);
				//}
				//$blogModel->setUrlKey($urlkey)->save();
                                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setBlogData(false);
 
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBlogData($this->getRequest()->getPost());
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
                $blogModel = Mage::getModel('blog/blog');
               
                $blogModel->setId($this->getRequest()->getParam('id'))
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
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('blog/adminhtml_blog_grid')->toHtml()
        );
    }
    
    public function massDeleteAction()
    {
        if(is_array($this->getRequest()->getParam('blog_id'))) {
        	try{
                $blogIds = $this->getRequest()->get('blog_id');
            	foreach($blogIds as $k => $v){
            	   Mage::getModel('blog/blog')->setId($v)->delete();
            	}
               Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item(s) were successfully deleted'));
               $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }
}