<?php
class Iconic_Job_Block_Adminhtml_Job_Grid_Renderer_Location
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }else{
        	$html = '';
        	$data = $row->getData($this->getColumn()->getIndex());
			$locIds = explode(',', $data);
			foreach($locIds as $locId){
				if($locId){
					$cat = Mage::getModel('job/country')->load($locId);
					$html .= $cat->getName();
					if($locId != end($locIds)){
						$html .= ', ';
					}
				}
			}
			return $html;
        }
    }
}