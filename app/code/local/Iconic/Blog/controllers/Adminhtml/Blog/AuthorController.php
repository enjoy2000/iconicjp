<?php
 
class Iconic_Blog_Adminhtml_Blog_AuthorController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('blog/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Author Manager'), Mage::helper('adminhtml')->__('Author Manager'));
        return $this;
    }   
   
    public function indexAction() {
        $this->_initAction(); 
        $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_author'));
        $this->renderLayout();
    }
 
    public function editAction()
    {
        $blogId     = $this->getRequest()->getParam('id');
        $blogModel  = Mage::getModel('blog/author')->load($blogId);
 
        if ($blogModel->getId() || $blogId == 0) {
 
            Mage::register('author_data', $blogModel);
 
            $this->loadLayout();
            $this->_setActiveMenu('blog/items');
           
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Author'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Author Details'));
           
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('blog/adminhtml_author_edit'))
                 ->_addLeft($this->getLayout()->createBlock('blog/adminhtml_author_edit_tabs'));
               
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
                $blogModel = Mage::getModel('blog/author');
               
                $blogModel->setData($postData)
                	->setId($this->getRequest()->getParam('id'))
                    ->save();
               
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
                $blogModel = Mage::getModel('blog/author');
               
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
     * Product grid for AJAX author.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('blog/adminhtml_author_grid')->toHtml()
        );
    }
}