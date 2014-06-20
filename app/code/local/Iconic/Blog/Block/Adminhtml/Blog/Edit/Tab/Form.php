<?php
 
class Iconic_Blog_Block_Adminhtml_Blog_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('blog_form', array('legend'=>Mage::helper('blog')->__('Blog Details')));
       	
		$fieldset->addField('iconic_id', 'text', array(
            'label'     => Mage::helper('blog')->__('Iconic ID'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'iconic_id',
        ));
        
		
        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('blog')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
        
		
		//get array categories
		$parentCategories = Mage::getModel('blog/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'industry'));
		foreach($parentCategories as $parent){
			$subCategories = Mage::getModel('blog/category')->getCollection();	
			$subCategories->addFieldToFilter('parentcategory_id',$parent->getParentcategoryId());
			$subCategories->setOrder('name','ASC');
			$arraySub = array();
			foreach($subCategories as $sub){
				$arraySub[] = 	array(
								'label'		=> $sub->getName(),
								'value' 	=> $sub->getCategoryId(),
				);				
			}
			$arrayCategories[] = array(
								'label'		=> $parent->getName(),
								'value'	=> $arraySub,
			);
		}
        $fieldset->addField('category_id', 'select', array(
            'label'     => Mage::helper('blog')->__('Industry Category'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'category_id',
            'values'	=> $arrayCategories,
        ));
		
		//get array categories
		$parentCategories = Mage::getModel('blog/parentcategory')->getCollection()->addFieldToFilter('group_category', array('eq'=>'function'));
		foreach($parentCategories as $parent){
			$subCategories = Mage::getModel('blog/category')->getCollection();	
			$subCategories->addFieldToFilter('parentcategory_id',$parent->getParentcategoryId());
			$subCategories->setOrder('name','ASC');
			$arraySub = array();
			foreach($subCategories as $sub){
				$arraySub[] = 	array(
								'label'		=> $sub->getName(),
								'value' 	=> $sub->getCategoryId(),
				);				
			}
			$arrayFunction[] = array(
								'label'		=> $parent->getName(),
								'value'	=> $arraySub,
			);
		}
        $fieldset->addField('function_category_id', 'select', array(
            'label'     => Mage::helper('blog')->__('Function Category'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'function_category_id',
            'values'	=> $arrayFunction,
        ));
		
		//get location values
		$countries = Mage::getModel('blog/country')->getCollection();
		foreach($countries as $country){
			$arrayLocation[] = array(
								'label' => $country->getName(),
								'value' => $country->getId(),
			);
		}
		
		$fieldset->addField('location_id', 'select', array(
            'label'     => Mage::helper('blog')->__('Location'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'location_id',
            'values'	=> $arrayLocation,
        ));
		$fieldset->addField('location_text', 'text', array(
            'label'     => Mage::helper('blog')->__('Area'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'location_text',
        ));
		
		//get level values
		$levelModel = Mage::getModel('blog/level')->getCollection();
		foreach($levelModel as $level){
			$arrayLevel[] = array(
							'label'		=> $level->getName(),
							'value'		=> $level->getLevelId(),
			);
		}
		$fieldset->addField('blog_level', 'select', array(
            'label'     => Mage::helper('blog')->__('Blog Level'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'blog_level',
            'values'	=> $arrayLevel,
        ));
		
		$fieldset->addField('blog_salary', 'text', array(
            'label'     => Mage::helper('blog')->__('Salary From'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'blog_salary',
            'value'		=> '0',
        ));
		
		$fieldset->addField('blog_salary_to', 'text', array(
            'label'     => Mage::helper('blog')->__('Salary To'),
            'required'  => false,
            'name'      => 'blog_salary_to',
            'value'		=> '0',
        ));
		
		$fieldset->addField('blog_salary_currency', 'select', array(
            'label'     => Mage::helper('blog')->__('Salary Currency'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'blog_salary_currency',
            'values'    => array(
						array(
							'label' => 'VND',
							'value' => 'VND'
						),
						array(
							'label' => 'USD',
							'value' => 'USD'
						),
			),
            'value'		=> 'VND',
        ));
		
		$fieldset->addField('blog_salary_type', 'select', array(
            'label'     => Mage::helper('blog')->__('Salary Type'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'blog_salary_type',
            'values'    => array(
						array(
							'label' => 'Gross',
							'value' => 'Gross'
						),
						array(
							'label' => 'NET',
							'value' => 'NET'
						),
			),
            'value'		=> 'Gross',
        ));
		//blog ammount
		$fieldset->addField('amount', 'text', array(
            'label'     => Mage::helper('blog')->__('Blog Amount'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'amount',
        ));
		//blog language
		$languages = Mage::getModel('blog/language')->getCollection();
		foreach($languages as $lang){
			$arrayLang[] = array(
							'label' => $lang->getName(),
							'value' => $lang->getId(),
			);
		}
		$fieldset->addField('language_id', 'multiselect', array(
            'label'     => Mage::helper('blog')->__('Blog Language'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'language_id',
            'values'    => $arrayLang,
            'value'		=> $arrayLangValues,
        ));
		//get type values
		$typeModel = Mage::getModel('blog/type')->getCollection();
		foreach($typeModel as $type){
			$arrayType[] = array(
							'label'		=> $type->getName(),
							'value'		=> $type->getTypeId(),
			);
		}
		$fieldset->addField('blog_type', 'select', array(
            'label'     => Mage::helper('blog')->__('Blog Type'),
            'name'     => 'blog_type',
            'required'  => true,
            'values'    => $arrayType,
        ));
		
		//get author values
		$author = Mage::getModel('blog/author')->getCollection();
		foreach($author as $type){
			$arrayAuthor[] = array(
							'label'		=> $type->getName(),
							'value'		=> $type->getId(),
			);
		}
		$fieldset->addField('author_id', 'multiselect', array(
            'label'     => Mage::helper('blog')->__('Author Tags'),
            'name'     => 'author_id',
            'required'  => true,
            'values'    => $arrayAuthor,
        ));
       
        $fieldset->addField('requirement', 'editor', array(
            'name'      => 'requirement',
            'label'     => Mage::helper('blog')->__('Requirement'),
            'title'     => Mage::helper('blog')->__('Requirement'),
            'style'     => 'width:98%; height:200px;',
			'config' 	=> Mage::getSingleton('cms/wysiwyg_config')->getConfig(), 
			'wysiwyg' 	=> true, 
            'required'  => true,
        ));
		
		$fieldset->addField('info', 'editor', array(
            'name'      => 'info',
            'label'     => Mage::helper('blog')->__('Info'),
            'title'     => Mage::helper('blog')->__('Info'),
            'style'     => 'width:98%; height:200px;',
			'config' 	=> Mage::getSingleton('cms/wysiwyg_config')->getConfig(), 
			'wysiwyg' 	=> true, 
            'required'  => true,
        ));
       
        if ( Mage::getSingleton('adminhtml/session')->getBlogData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ( Mage::registry('blog_data') ) {
            $form->setValues(Mage::registry('blog_data')->getData());
        }
        return parent::_prepareForm();
    }
}