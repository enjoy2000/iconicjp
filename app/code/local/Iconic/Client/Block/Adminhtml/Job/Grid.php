<?php
 
class Iconic_Client_Block_Adminhtml_Job_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('clientGrid');
        // This is the primary key of the database
        $this->setDefaultSort('job_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('job/job')->getCollection()
							->addFieldToFilter('customer_id', array('neq'=>NULL));
		
		//JOIN TABLES TO SHOW ON NAME ON GRID
		/* @var $collection Iconic_Client_Model_Mysql4_Type_Collection */
		
		$collection->getSelect()->join(array("t" => $collection->getTable('job/type')), 
			"main_table.job_type = t.type_id", "t.name as t_name");
		/* @var $collection Iconic_Client_Model_Mysql4_Level_Collection */
		
		$collection->getSelect()->join(array("l" => $collection->getTable('job/level')), 
			"main_table.job_level = l.level_id", "l.name as l_name");
		
		
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
	
    protected function _prepareColumns()
    {
        $this->addColumn('job_id', array(
            'header'    => Mage::helper('job')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'job_id',
        ));
		
		$this->addColumn('status', array(
            'header'    => Mage::helper('job')->__('Status'),
            'index'     => 'status',
        ));
 
        $this->addColumn('title', array(
            'header'    => Mage::helper('job')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
            'width'		=> '400px',
        ));
		
		$this->addColumn('customer_id', array(
            'header'    => Mage::helper('job')->__('CompanyID'),
            'index'     => 'customer_id',
        ));
        
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('customer')->__('Industry'),
            'index'     => 'category_id',
            'filter'	=> false,
            'sortable'  => true,
            'renderer'  => 'Iconic_Client_Block_Adminhtml_Job_Grid_Renderer_Category',
        ));
		
		$this->addColumn('function_category_id', array(
            'header'    => Mage::helper('customer')->__('Function'),
            'index'     => 'function_category_id',
            'filter'	=> false,
            'sortable'  => true,
            'renderer'  => 'Iconic_Client_Block_Adminhtml_Job_Grid_Renderer_Category',
        ));
                
        $this->addColumn('location_id', array(
            'header'    => Mage::helper('job')->__('Location'),
            'index'     => 'location_id',
            'filter_index'=>'location_id'            
        ));
                
        $this->addColumn('job_level', array(
            'header'    => Mage::helper('job')->__('Level'),
            'index'     => 'l_name',
            'filter_index'=>'l.name'
        ));
                
        $this->addColumn('t_name', array(
            'header'    => Mage::helper('job')->__('Type'),
            'index'     => 't_name',
            'filter_index'=>'t.name'
        ));
 
        $this->addColumn('created_time', array(
            'header'    => Mage::helper('job')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '80px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_time',
        ));
 
        return parent::_prepareColumns();
    }


    protected function _prepareMassaction()
    {
    	$this->setMassactionIdField('job_id');
    	$this->getMassactionBlock()->setFormFieldName('job_id');
    
    	$this->getMassactionBlock()->addItem('delete', array(
    			'label'    => Mage::helper('job')->__('Delete'),
    			'url'      => $this->getUrl('*/*/massDelete'),
    			'confirm'  => Mage::helper('job')->__('Are you sure?')
    	));
    	return $this;
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