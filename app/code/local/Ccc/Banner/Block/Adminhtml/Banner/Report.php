<?php

class Ccc_Banner_Block_Adminhtml_Banner_Report extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('name');
		$this->setDefaultDir('ASC');
		$this->setPagerVisibility(false);
		$this->setFilterVisibility(false);
		$this->setHeadersVisibility(false);
		// $this->mass
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->joinAttribute('instock_date', 'catalog_product/instock_date', 'entity_id', null, 'right');
		// $collection->addFieldtoSelect('at_instock_date')
		// $collection->groupByAttribute('instock_date');
		$collection->getSelect()->columns(['count' => 'COUNT(*)']);
		$today = date('Y-m-d');
		$todayPlus25Days = date('Y-m-d', strtotime('+25 days'));

		// Use CASE statement to create custom grouping
		$collection->getSelect()->columns(
			array(
				'date_group' => new Zend_Db_Expr("
        CASE 
            WHEN at_instock_date.value <= {$todayPlus25Days} THEN 'within_25_days' 
            ELSE 'above_25_days' 
        END"))
		);
		// echo $collection->getSelect(); die;
		// $collection->getSelect()->group('date_group');

		// echo get_class($collection); die;

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	// public function addColumn($columnId, $column){
	// 	if(isset($column['is_allowed']) && $column['is_allowed'] === false){
	// 		return;
	// 	}
	// 	return parent::addColumn($columnId, $column);
	// }
	protected function _prepareColumns()
	{
		// 	$isAllowed = Mage::getSingleton('admin/session')->isAllowed('banner/field/name');
		$this->addColumn(
			'date_group',
			array(
				'width' => '30px',
				'header' => "banner id",
				'align' => 'right',
				'index' => 'date_group',
			)
		);

		$this->addColumn(
			'count',
			array(
				'header' => Mage::helper('banner')->__('Banner Name'),
				'align' => 'left',
				'index' => 'count',
				// 'is_allowed' => $isAllowed
			)
		);

		// 	$this->addColumn(
		// 		'image',
		// 		array(
		// 			'header' => Mage::helper('banner')->__('Image'),
		// 			'index' => 'image',
		// 			'is_allowed' => $isAllowed
		// 		)
		// 	);

		// 	$this->addColumn(
		// 		'content',
		// 		array(
		// 			'header' => Mage::helper('banner')->__('Content'),
		// 			'index' => 'content',
		// 			'type' => 'text',
		// 		)
		// 	);

		// 	$this->addColumn(
		// 		'show_on',
		// 		array(
		// 			'header' => Mage::helper('banner')->__('Show On'),
		// 			'index' => 'show_on',
		// 			'type' => 'text',
		// 		)
		// 	);
		// 	$this->addColumn(
		// 		'status',
		// 		array(
		// 			'header' => Mage::helper('banner')->__('Status'),
		// 			'index' => 'status',
		// 			'type' => 'options',
		// 			'options' => array(
		// 				0 => Mage::helper('banner')->__('Disabled'),
		// 				1 => Mage::helper('banner')->__('Enabled')
		// 			)
		// 		)
		// 	);

		// 	return parent::_prepareColumns();
	}

	// protected function _prepareMassAction()
	// {
	// 	$this->setMassactionIdField('banner_id');
	// 	$this->getMassactionBlock()->setFormFieldName('banner');

	// 	$this->getMassactionBlock()->addItem(
	// 		'delete',
	// 		array(
	// 			'label' => Mage::helper('banner')->__('Delete'),
	// 			'url' => $this->getUrl('*/*/massDelete'),
	// 			'confirm' => Mage::helper('banner')->__('Are you sure?')
	// 		)
	// 	);

	// 	$this->getMassactionBlock()->addItem(
	// 		'status',
	// 		array(
	// 			'label' => Mage::helper('banner')->__('Change status'),
	// 			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
	// 			'additional' => array(
	// 				'visibility' => array(
	// 					'name' => 'status',
	// 					'type' => 'select',
	// 					'class' => 'required-entry',
	// 					'label' => Mage::helper('banner')->__('Status'),
	// 					'values' => array(
	// 						array('label' => '', 'value' => ''),
	// 						array('label' => 'Disabled', 'value' => 0),
	// 						array('label' => 'Enabled', 'value' => 1)
	// 					)
	// 				)
	// 			)
	// 		)
	// 	);
	// }
}