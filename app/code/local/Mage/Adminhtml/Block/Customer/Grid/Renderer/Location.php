<?php
class Mage_Adminhtml_Block_Customer_Grid_Renderer_Location
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }else{
        	$data = $row->getData($this->getColumn()->getIndex());
			$html = Mage::getModel('job/listlocation')->load($data)->getName();
			return $html;
        }
    }
}