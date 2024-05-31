<?php

class Ccc_Outlook_Block_Adminhtml_Configuration_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct()
	{
		parent::__construct();
		$this->setId('configurationGrid');
		$this->setSaveParametersInSession(true);
	}
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('outlook/configuration')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn(
			'configuration_id',
			array(
				'width' => '30px',
				'header' => "Configuration Id",
				'align' => 'right',
				'index' => 'configuration_id',
			)
		);

		$this->addColumn(
			'client_id',
			array(
				'header' => Mage::helper('outlook')->__('Client Id'),
				'align' => 'left',
				'index' => 'client_id',
			)
		);

		$this->addColumn(
			'client_secret',
			array(
				'header' => Mage::helper('outlook')->__('Client Secret'),
				'index' => 'client_secret',
				'type' => 'text',
			)
		);

		$this->addColumn(
			'api_url',
			array(
				'header' => Mage::helper('outlook')->__('API URL'),
				'index' => 'api_url',
				'type' => 'text',
			)
		);

		$this->addColumn(
			'access_token',
			array(
				'width' => '50px',
				'header' => Mage::helper('outlook')->__('Access Token'),
				'index' => 'access_token',
				'type' => 'text',
			)
		);
		$this->addColumn(
			'is_active',
			array(
				'header' => Mage::helper('outlook')->__('Is Active'),
				'index' => 'is_active',
				'type' => 'options',
				'options' => array(
					2 => Mage::helper('outlook')->__('Yes'),
					1 => Mage::helper('outlook')->__('No')
				)
			)
		);

		return parent::_prepareColumns();
	}
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('configuration_id' => $row->getId()));
	}

}