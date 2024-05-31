<?php

class Ccc_FilterReport_Block_Adminhtml_Report_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('filterreportGrid');
		// $this->setDefaultSort('name');
		// $this->setDefaultDir('ASC');
		// $this->setUseAjax(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('filterreport/report')->getCollection();
		/** 
		 * @var Mage_Cms_Model_Mysql4_Block_Collection $collection  
		 */
		// echo Mage::getModel('filterreport/report')->getResource()->getTable(); die;
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns()
	{
		$this->addColumn(
			'id',
			array(
				'width' => '30px',
				'header' => "Report id",
				'align' => 'right',
				'index' => 'id',
			)
		);

		$this->addColumn(
			'user_id',
			array(
				'header' => Mage::helper('filterreport')->__('User Id'),
				'align' => 'left',
				'index' => 'user_id',
			)
		);

		$this->addColumn(
			'report_type',
			array(
				'header' => Mage::helper('filterreport')->__('Report Type'),
				'index' => 'report_type',
				'type' => 'options',
				'options' => array(
					1 => Mage::helper('filterreport')->__('Product'),
					2 => Mage::helper('filterreport')->__('Customer')
				)
			)
		);

		$this->addColumn(
			'filter_data',
			array(
				'header' => Mage::helper('filterreport')->__('Filter Data'),
				'index' => 'filter_data',
				'type' => 'text',
			)
		);

		$this->addColumn(
			'is_active',
			array(
				'header' => Mage::helper('filterreport')->__('Is Active'),
				'index' => 'is_active',
				'type' => 'options',
				'options' => array(
					0 => Mage::helper('filterreport')->__('No'),
					1 => Mage::helper('filterreport')->__('Yes')
				)
			)
		);
		// $this->addColumn(
		// 	'status',
		// 	array(
		// 		'header' => Mage::helper('filterreport')->__('Status'),
		// 		'index' => 'status',
		// 		'type' => 'options',
		// 		'options' => array(
		// 			0 => Mage::helper('banner')->__('Disabled'),
		// 			1 => Mage::helper('banner')->__('Enabled')
		// 		)
		// 	)
		// );

		return parent::_prepareColumns();
	}

	protected function _prepareMassAction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('report');


		$this->getMassactionBlock()->addItem(
			'status',
			array(
				'label' => Mage::helper('filterreport')->__('Change Active Status'),
				'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
				'additional' => array(
					'visibility' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('filterreport')->__('Status'),
						'values' => array(
							array('label' => '', 'value' => ''),
							array('label' => 'Yes', 'value' => 1),
							array('label' => 'No', 'value' => 0)
						)
					)
				)
			)
		);
	}
}