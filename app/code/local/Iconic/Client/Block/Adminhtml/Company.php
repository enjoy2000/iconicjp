<?php
class Iconic_Client_Block_Adminhtml_Company extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_company';
        $this->_blockGroup = 'client';
        $this->_headerText = Mage::helper('client')->__('Companies Manager');
		$this->_removeButton('add');
        parent::__construct();
    }
}