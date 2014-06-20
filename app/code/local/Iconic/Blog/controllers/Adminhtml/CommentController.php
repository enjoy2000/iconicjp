<?php
/**
 * ICONIC Co., LTD
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ICONIC License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * 
 *
 * @category   ICONIC
 * @package    Iconic_Blog
 * @copyright  Copyright (c) 2012 ICONIC Co., LTD (http://iconic-jp.com)
 * @license    
 */

class Iconic_Blog_Adminhtml_CommentController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
    }

    /**
     * Init actions
     *
     */
    protected function _initAction() {
        // load layout, set active menu
        $this->loadLayout()
            ->_setActiveMenu('blog/comment');
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('blog/adminhtml_comment'))
            ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('blog/comment');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Page access error'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('blog_data', $model);

        $this->loadLayout()
            ->_addContent($this->getLayout()->createBlock('blog/adminhtml_comment_edit'))
            ->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blog/comment');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $model->setUpdateTime(now())
                    ->save();
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('blog')->__('Comment was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('blog/comment');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('blog')->__('Comment was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massApproveAction()
    {
        $commentIds = $this->getRequest()->getParam('comments');
        if (!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select comment(s)'));
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $model = Mage::getSingleton('blog/comment')
                        ->load($commentId)
                        ->setCommentStatus(Iconic_Blog_Helper_Data::APPROVED_STATUS)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()
                    ->addSuccess($this
                    ->__('%d comment(s) have been successfully approved',
                    count($commentIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massUnapproveAction()
    {
        $commentIds = $this->getRequest()->getParam('comments');
        if (!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select comment(s)'));
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $model = Mage::getSingleton('blog/comment')
                        ->load($commentId)
                        ->setCommentStatus(Iconic_Blog_Helper_Data::UNAPPROVED_STATUS)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()
                    ->addSuccess($this
                    ->__('%d comment(s) have been successfully unapproved', count($commentIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $commentIds = $this->getRequest()->getParam('comments');
        if (!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')->__('Please select comment(s)'));
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $model = Mage::getModel('blog/comment')->load($commentId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                    ->__('%d comments(s) have been successfully deleted',
                    count($commentIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
