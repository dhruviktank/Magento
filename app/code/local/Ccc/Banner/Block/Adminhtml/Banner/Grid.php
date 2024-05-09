<?php

class Ccc_Banner_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{


	public function __construct()
	{
		parent::__construct();
		$this->setId('bannerGrid');
		$this->setDefaultSort('name');
		$this->setDefaultDir('ASC');
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('banner/banner')->getCollection();
		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		if (!Mage::getSingleton('admin/session')->isAllowed('banner/actions/fieldlimit')) {
			$collection->getSelect()->limit(100);
			$this->setCollection($collection);
			$this->getCollection()->load();
		}
		$collection->addFieldToSelect("*");
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _preparePage()
	{
	if (!Mage::getSingleton('admin/session')->isAllowed('banner/actions/fieldlimit')) {
			$this->getCollection()->setPageSize(1);
			$this->getCollection()->setCurPage(1);
		}
	}
	public function addColumn($columnId, $column){
		if(isset($column['is_allowed']) && $column['is_allowed'] === false){
			return;
		}
		return parent::addColumn($columnId, $column);
	}
	protected function _prepareColumns()
	{
		$isAllowed = Mage::getSingleton('admin/session')->isAllowed('banner/field/name');
		$this->addColumn(
			'banner_id',
			array(
				'width' => '30px',
				'header' => "banner id",
				'align' => 'right',
				'index' => 'banner_id',
			)
		);

		$this->addColumn(
			'name',
			array(
				'header' => Mage::helper('banner')->__('Banner Name'),
				'align' => 'left',
				'index' => 'name',
				'is_allowed' => $isAllowed
			)
		);

		$this->addColumn(
			'image',
			array(
				'header' => Mage::helper('banner')->__('Image'),
				'index' => 'image',
				'is_allowed' => $isAllowed
			)
		);

		$this->addColumn(
			'content',
			array(
				'header' => Mage::helper('banner')->__('Content'),
				'index' => 'content',
				'type' => 'text',
			)
		);

		$this->addColumn(
			'show_on',
			array(
				'header' => Mage::helper('banner')->__('Show On'),
				'index' => 'show_on',
				'type' => 'text',
			)
		);
		$this->addColumn(
			'status',
			array(
				'header' => Mage::helper('banner')->__('Status'),
				'index' => 'status',
				'type' => 'options',
				'options' => array(
					0 => Mage::helper('banner')->__('Disabled'),
					1 => Mage::helper('banner')->__('Enabled')
				)
			)
		);

		return parent::_prepareColumns();
	}

	protected function _prepareMassAction()
	{
		$this->setMassactionIdField('banner_id');
		$this->getMassactionBlock()->setFormFieldName('banner');

		$this->getMassactionBlock()->addItem(
			'delete',
			array(
				'label' => Mage::helper('banner')->__('Delete'),
				'url' => $this->getUrl('*/*/massDelete'),
				'confirm' => Mage::helper('banner')->__('Are you sure?')
			)
		);

		$this->getMassactionBlock()->addItem(
			'status',
			array(
				'label' => Mage::helper('banner')->__('Change status'),
				'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
				'additional' => array(
					'visibility' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('banner')->__('Status'),
						'values' => array(
							array('label' => '', 'value' => ''),
							array('label' => 'Disabled', 'value' => 0),
							array('label' => 'Enabled', 'value' => 1)
						)
					)
				)
			)
		);
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('banner_id' => $row->getId()));
	}

}