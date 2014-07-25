<?php
class Iconic_Job_Block_Adminhtml_Job_Grid_Renderer_Category
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }else{
        	$data = $row->getData($this->getColumn()->getIndex());
			$cat = Mage::getModel('job/category')->load($data);
			$parent = Mage::getModel('job/parentcategory')->load($cat->getParentcategoryId());
			return $parent->getName();
        }
    }
}
