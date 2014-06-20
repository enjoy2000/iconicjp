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

class Iconic_Blog_Block_Adminhtml_Category_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $this->getPosition();
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $fieldset = $form->addFieldset('category_form',
            array('legend'=>Mage::helper('blog')->__('Category Information')));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('blog')->__('Title'),
            'title'     => Mage::helper('blog')->__('Title'),
            'name'      => 'title',
            'required'  => true
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('blog')->__('URL Key'),
            'title'     => Mage::helper('blog')->__('URL Key'),
            'name'      => 'url_key',
            'required'  => true
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('blog')->__('Sort Order'),
            'name'      => 'sort_order',
        ));

        $fieldset->addField('sort_id', 'hidden', array(
                'name' => 'sort_id',
                'values' => $this->getPosition(),
        ));
        if ($this->getRequest()->getParam('parent_id') == null) {
            if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
                    $data = array('sort_id' => $this->getPosition());
                    Mage::getSingleton('adminhtml/session')->setBlogData($data);
            } else if ($data = Mage::registry('blog_data')) {
                if ($data->getSortId() == null) {
                    $params = array('sort_id' => $this->getPosition());
                    $data->setData($params);
                    Mage::unregister('blog_data');
                    Mage::register('blog_data', $data);
                }
            }
        }

        /**
         * Check is single store mode
         */
        $parentStore = array();
        if ($pid = $this->getRequest()->getParam('parent_id')) {
            $fieldset->addField('parent_id', 'hidden', array(
                'name' => 'parent_id',
                'values' => $pid,
            ));
            $fieldset->addField('level', 'hidden', array(
                'name' => 'level',
                'values' => $level,
            ));
            $category = Mage::getModel('blog/category')->load($pid);
            if ($lev = $category->getLevel()) {
                $level = $lev + 1;
            } else {
                $level = '1';
            }
            if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
                $data = array('parent_id' => $pid, 'level' => $level, 'sort_id' => $this->getPosition());
                Mage::getSingleton('adminhtml/session')->setBlogData($data);
            } else if ($data = Mage::registry('blog_data')) {
                $params = array('parent_id' => $pid, 'level' => $level, 'sort_id' => $this->getPosition());
                $data->setData($params);
                Mage::unregister('blog_data');
                Mage::register('blog_data', $data);
            }
            $store = $category->getStoreId();

            $stores = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
            foreach ($stores as $val) {
                if (is_array($val['value'])) {
                    foreach ($val['value'] as $st) {
                        if ($st['value'] == $store[0]) {
                            $parentStore[] = $st;
                        }
                    }
                } else {
                    if ($val['value'] == $store[0]) {
                        $parentStore[] = $val;
                    }
                }
            }

        } else {
            $fieldset->addField('level', 'hidden', array(
                'name' => 'level',
                'values' => 0,
            ));

            $fieldset->addField('parent_id', 'hidden', array(
                'name' => 'parent_id',
                'values' => 0,
            ));
        }

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => count($parentStore) ? $parentStore : Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }

        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('blog')->__('Keywords'),
            'title' => Mage::helper('blog')->__('Meta Keywords'),
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('blog')->__('Description'),
            'title' => Mage::helper('blog')->__('Meta Description'),
        ));

        if ( Mage::getSingleton('adminhtml/session')->getBlogData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ( Mage::registry('blog_data') ) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function getPosition()
    {
        if ($id = $this->getRequest()->getParam('parent_id')) {
            $collection = Mage::getModel('blog/category')->getCollection();
            $collection->getSelect()->order('main_table.sort_id DESC');
            $collection->getSelect()->where('main_table.parent_id =?', $id);
            $collection->getSelect()->limit(1);
            $sortId = $collection->getData('sort_id');
            if (count($sortId) < 1) {
                unset($sortId);
                $collectionNew = Mage::getModel('blog/category')->getCollection();
                $collectionNew->getSelect()->where('main_table.category_id =?', $id);

            $sortId = $collectionNew->getData('sort_id');
            }
            $position = $sortId[0]['sort_id'] + 1;
            if (count($this->checkPosition($position)) > 0) {
                $this->updatePosition($position);
            }
            return $position;
        } else if ($id = $this->getRequest()->getParam('id')) {
            $collection = Mage::getModel('blog/category')->getCollection();
            $collection->getSelect()->where('main_table.category_id =?', $id);
            $sortId = $collection->getData('sort_id');
            $position = $sortId[0]['sort_id'];
            return $position;
        } else {
            $collection = Mage::getModel('blog/category')->getCollection();
            $collection->getSelect()->order('main_table.sort_id DESC');
            $collection->getSelect()->limit(1);
            if (count($collection) < 1) {
                $position = 0;
            } else {
                $sortId = $collection->getData('sort_id');
                $position = $sortId[0]['sort_id'] + 1;
                if (count($this->checkPosition($position)) > 0) {
                    $this->updatePosition($position);
                }
            }
            return $position;
        }
    }

    protected function checkPosition($pos)
    {
        $collection = Mage::getModel('blog/category')->getCollection();
        $collection->getSelect()->where('sort_id =?', $pos);
        return $collection->getData();
    }

    protected function updatePosition($pos)
    {
        $collection = Mage::getModel('blog/category')->getCollection();

        foreach ($collection as $category) {
            if ($category->getSortId() >= $pos) {
                $category->setSortId($category->getSortId() + 10);
                try {
                    $category->save();
                } catch (Exception $ex) {
                    echo 'you have a problem with saving category!!!!' . "\n";
                }
            }
        }
    }
}
