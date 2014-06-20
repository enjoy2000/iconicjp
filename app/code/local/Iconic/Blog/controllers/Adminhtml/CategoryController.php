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

class Iconic_Blog_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
    }

    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        // load layout, set active menu
        $this->loadLayout()
            ->_setActiveMenu('blog/category');
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction() {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('blog/adminhtml_category'))
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('blog/category');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Page access error'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        Mage::register('blog_data', $model);

        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('blog/adminhtml_category_edit'));

        $this->renderLayout();
    }


    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blog/category');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('blog')->__('Category was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
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
                $model = Mage::getModel('blog/category');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('blog')->__('Category was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('category');
        if (!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')->__('Please select categories'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $model = Mage::getModel('blog/category')->load($categoryId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                        ->__('%d categories have been successfully deleted',
                        count($categoryIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
