<?php
 
class Iconic_Job_Block_Adminhtml_Request_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('requestGrid');
        // This is the primary key of the database
        $this->setDefaultSort('request_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('job/request')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('request_id', array(
            'header'    => Mage::helper('job')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'request_id',
        ));
		
		$this->addColumn('email', array(
            'header'    => Mage::helper('job')->__('Email'),
            'align'     =>'left',
            'index'     => 'email',
        ));
		
		$this->addColumn('name', array(
            'header'    => Mage::helper('job')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
 
        $this->addColumn('name', array(
            'header'    => Mage::helper('job')->__('Full Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
		
		$this->addColumn('company_name', array(
            'header'    => Mage::helper('job')->__('Company Name'),
            'align'     =>'left',
            'index'     => 'company_name',
        ));
		
		$this->addColumn('job_content', array(
            'header'    => Mage::helper('job')->__('Job Content'),
            'align'     =>'left',
            'index'     => 'job_content',
        ));
 
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}