<?php
class Mage_Adminhtml_Block_Customer_Grid_Renderer_Cv
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }else{
        	$customer = Mage::getModel('customer/customer')->load($row->getId());
			$html = '<a target="_blank" href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB). 'tmp/' . $customer->getEmail() . '.xlsx">Link</a>';
			return $html;
        }
    }
}