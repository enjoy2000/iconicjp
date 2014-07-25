<?php
class Iconic_Client_Block_Job extends Mage_Core_Block_Template
{
	protected function _prepareLayout(){
		parent::_prepareLayout();
	}
	
	public function getCategoryList($group, $data){
		$industry =  Mage::getModel('job/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>$group));
		$option = '';
		foreach($industry as $parent){
			$cats = Mage::getModel('job/category')->getCollection()->addFieldToFilter('parentcategory_id', array('eq'=>$parent->getId()));
			foreach($cats as $cat){
				$catName = Mage::helper('job')->getTransName($cat);
				$selected = '';
				if($cat->getId() == $data){
					$selected = ' selected="selected"';
				}
				$option .= "<option{$selected} value=\"{$cat->getId()}\">{$catName}</option>";
			}
		}
		
		return $option;
	}
	
	public function getLocationList($data){
		$locations = Mage::getModel('job/location')->getCollection();
		$option = '';
		foreach($locations as $loc){
			$locName = Mage::helper('job')->getTransName($loc);
			$selected = '';
			if($loc->getId() == $data){
				$selected = ' selected="selected"';
			}
			$option .= "<option{$selected} value=\"{$loc->getId()}\">{$locName}</option>";
		}
		return $option;
	}

	public function getLevelList($data){
		$levels = Mage::getModel('job/level')->getCollection();
		$option = '';
		foreach($levels as $level){
			$levelName = Mage::helper('job')->getTransName($level);
			$selected = '';
			if($level->getId() == $data){
				$selected = ' selected="selected"';
			}
			$option .= "<option{$selected} value=\"{$level->getId()}\">{$levelName}</option>";
		}
		return $option;
	}

	public function getTypeList($data){
		$types = Mage::getModel('job/type')->getCollection();
		$option = '';
		foreach($types as $type){
			$typeName = Mage::helper('job')->getTransName($type);
			$selected = '';
			if($type->getId() == $data){
				$selected = ' selected="selected"';
			}
			$option .= "<option{$selected} value=\"{$type->getId()}\">{$typeName}</option>";
		}
		return $option;
	}
	
}