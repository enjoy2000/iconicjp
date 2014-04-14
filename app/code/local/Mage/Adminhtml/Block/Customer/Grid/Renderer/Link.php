<?php
class Mage_Adminhtml_Block_Customer_Grid_Renderer_Link
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }else{
        	$data = $row->getData($this->getColumn()->getIndex());
			$links = explode(';',$data);
			$html = '';
			foreach($links as $link){
				$html .= '<a target="_blank" href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB). 'files/' . $row->getId() . '/' . $link . '">Link</a><br />';
			}
			return $html;
        }
    }
}