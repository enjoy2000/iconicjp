<?php
class Iconic_Client_Block_Job extends Mage_Core_Block_Template
{
	protected function _prepareLayout(){
		parent::_prepareLayout();
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('client')->__('求人を投稿する'));
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
		if(!$this->hasData('locationList')){
			$locations = Mage::getModel('job/country')->getCollection();
			$option = '';
			foreach($locations as $loc){
				$locName = Mage::helper('job')->getTransName($loc);
				$selected = '';
				if($loc->getId() == $data){
					$selected = ' selected="selected"';
				}
				$option .= "<option{$selected} value=\"{$loc->getId()}\">{$locName}</option>";
			}
			$this->setData('locationList', $option);
		}
		return $this->getData('locationList');
	}

	public function getLevelList($data){
		if(!$this->hasData('levelList')){
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
			$this->setData('levelList', $option);
		}
		return $this->getData('levelList');
	}

	public function getTypeList($data){
		if(!$this->hasData('typeList')){
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
			$this->setData('typeList', $option);
		}
		return $this->getData('typeList');
	}
	
	public function getLanguageList($data){
		if(!$this->hasData('languageList')){
			$languages = Mage::getModel('job/language')->getCollection();
			$levels = Mage::getModel('job/langlevel')->getCollection();
			$langHtml = '';
			foreach($languages as $lang){
				$langHtml .= '<div class="lang clearfix '.strtolower($lang->getNameEn()).'">';
				$langHtml .= '<div class="fll">'.Mage::helper('job')->getTransName($lang).'</div>';
				$langHtml .= '<div class="flr">';
				foreach($levels as $level){
					$checked = '';
					if($data[$lang->getId()] == $level->getId()){
						$checked = ' checked';
					}
					$langHtml .= '<div class="lang-level inline-b">';
					$langHtml .= '<input'.$checked.' type="radio" name="language_id['.$lang->getId().']" value="'.$level->getId().'" />';
					$langHtml .= Mage::helper('job')->getTransName($level);
					$langHtml .= '</div>';
				}
				$langHtml .= '</div>';
				$langHtml .= '</div>';
			}
			$this->setData('languageList', $langHtml);
		}
		return $this->getData('languageList');
	}
	
	public function getFeatureList($data){
		if(!$this->hasData('featureList')){
			$features = Mage::getModel('job/feature')->getCollection();
			$featureHtml = '';
			foreach($features as $feature){
				$checked = '';
				// check option is checked or not 
				if(in_array($feature->getId(), $data)){
					$checked = ' checked';
				}
				$featureHtml .= '<div class="feature inline-b">';
				$featureHtml .= '<input'.$checked.' type="checkbox" name="feature_id[]" value="'.$feature->getId().'" />';
				$featureHtml .= Mage::helper('job')->getTransName($feature);
				$featureHtml .= '</div>';
			}
			$this->setData('featureList', $featureHtml);
		}
		return $this->getData('featureList');
	}

}