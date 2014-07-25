<?php
class Iconic_Client_Block_Adminhtml_Company_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customerGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToFilter('website_id', array('eq'=>2))
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('created_at')
            ->addAttributeToSelect('group_id')			
			->addAttributeToSelect('company_name')	
			->addAttributeToSelect('company_address')
			->addAttributeToSelect('company_size')	
			->addAttributeToSelect('company_website')
			->addAttributeToSelect('createcv');
		
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  => 'number',
        ));
        $this->addColumn('firstname', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'firstname'
        ));
        $this->addColumn('email', array(
            'header'    => Mage::helper('customer')->__('Email'),
            'width'     => '150',
            'index'     => 'email'
        ));
		$this->addColumn('company_name', array(
            'header'    => Mage::helper('customer')->__('Company Name'),
            'index'     => 'company_name',
        ));
		$this->addColumn('company_address', array(
            'header'    => Mage::helper('customer')->__('Company Address'),
            'index'     => 'company_address',
        ));
		$this->addColumn('company_size', array(
            'header'    => Mage::helper('customer')->__('Company Size'),
            'index'     => 'company_size',
        ));
		$this->addColumn('company_website', array(
            'header'    => Mage::helper('customer')->__('Company Website'),
            'index'     => 'company_website',
        ));
        $this->addColumn('customer_since', array(
            'header'    => Mage::helper('customer')->__('Customer Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true
        ));
		
		
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header'    => Mage::helper('customer')->__('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index'     => 'website_id',
            ));
        }

        $this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('customer');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('customer')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('customer')->__('Are you sure?')
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=> true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }
}
