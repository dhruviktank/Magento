<?php

class Ccc_Salesman_Block_Adminhtml_Bonus_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_bonus_grid');
        $this->setDefaultDir('DESC');
        // $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesman/bonus')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'metric',
            array(
                'header' => Mage::helper('salesman')->__('Metric'),
                'align' => 'left',
                'index' => 'metric'
            )
        );
        $this->addColumn(
            'rank',
            array(
                'header' => Mage::helper('salesman')->__('Rank'),
                'align' => 'left',
                'index' => 'rank'
            )
        );
        $this->addColumn(
            'users_in_league',
            array(
                'header' => Mage::helper('salesman')->__('Users In League'),
                'align' => 'left',
                'index' => 'users_in_league'
            )
        );
        $this->addColumn(
            'start_date',
            array(
                'header' => Mage::helper('salesman')->__('Start Date'),
                'align' => 'left',
                'index' => 'start_date'
            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('salesman')->__('End Date'),
                'align' => 'left',
                'index' => 'updated_at'
            )
        );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('salesman')->__('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('salesman')->__('No'),
                    1 => Mage::helper('salesman')->__('Yes'),
                ),
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/configuration', array('entity_id' => $row->getId()));
    }
}