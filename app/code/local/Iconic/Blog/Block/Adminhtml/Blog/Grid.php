<?php
 
class Iconic_Blog_Block_Adminhtml_Blog_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('blogGrid');
        // This is the primary key of the database
        $this->setDefaultSort('blog_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('blog/blog')->getCollection();
		
		//JOIN TABLES TO SHOW ON NAME ON GRID
		/* @var $collection Iconic_Blog_Model_Mysql4_Type_Collection */
		
		$collection->getSelect()->join(array("t" => $collection->getTable('blog/type')), 
			"main_table.blog_type = t.type_id", "t.name as t_name");
		/* @var $collection Iconic_Blog_Model_Mysql4_Level_Collection */
		
		$collection->getSelect()->join(array("l" => $collection->getTable('blog/level')), 
			"main_table.blog_level = l.level_id", "l.name as l_name");
		/* @var $collection Iconic_Blog_Model_Mysql4_Location_Collection */
		
		$collection->getSelect()->join(array("la" => $collection->getTable('blog/country')), 
			"main_table.location_id = la.country_id", "la.name as la_name");
		
		
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
	
    protected function _prepareColumns()
    {
        $this->addColumn('blog_id', array(
            'header'    => Mage::helper('blog')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'blog_id',
        ));
		
		$this->addColumn('iconic_id', array(
            'header'    => Mage::helper('blog')->__('IconicId'),
            'index'     => 'iconic_id',
        ));
 
        $this->addColumn('title', array(
            'header'    => Mage::helper('blog')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));
        
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('customer')->__('Industry'),
            'index'     => 'category_id',
            'filter'	=> false,
            'sortable'  => true,
            'renderer'  => 'Iconic_Blog_Block_Adminhtml_Blog_Grid_Renderer_Category',
        ));
		
		$this->addColumn('function_category_id', array(
            'header'    => Mage::helper('customer')->__('Function'),
            'index'     => 'function_category_id',
            'filter'	=> false,
            'sortable'  => true,
            'renderer'  => 'Iconic_Blog_Block_Adminhtml_Blog_Grid_Renderer_Category',
        ));
                
        $this->addColumn('location_id', array(
            'header'    => Mage::helper('blog')->__('Location'),
            'index'     => 'la_name',
            'filter_index'=>'la.name'            
        ));
                
        $this->addColumn('blog_level', array(
            'header'    => Mage::helper('blog')->__('Level'),
            'index'     => 'l_name',
            'filter_index'=>'l.name'
        ));
                
        $this->addColumn('t_name', array(
            'header'    => Mage::helper('blog')->__('Type'),
            'index'     => 't_name',
            'filter_index'=>'t.name'
        ));
 
        $this->addColumn('created_time', array(
            'header'    => Mage::helper('blog')->__('Creation Time'),
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
    	$this->setMassactionIdField('blog_id');
    	$this->getMassactionBlock()->setFormFieldName('blog_id');
    
    	$this->getMassactionBlock()->addItem('delete', array(
    			'label'    => Mage::helper('blog')->__('Delete'),
    			'url'      => $this->getUrl('*/*/massDelete'),
    			'confirm'  => Mage::helper('blog')->__('Are you sure?')
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