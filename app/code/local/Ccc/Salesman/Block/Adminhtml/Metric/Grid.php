<?php
class Ccc_Salesman_Block_Adminhtml_Metric_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('salesman_metric_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesman/metric')->getCollection();
        $collection->setOrder('user_id', 'ASC');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('user_id', array(
            'header' => Mage::helper('salesman')->__('USER ID'),
            'align' => 'left',
            'index' => 'user_id'
        )
        );
        
        $collection = Mage::getResourceModel('admin/user_collection');
        $userNames = array();
        foreach ($collection as $item) {
            $userNames[$item->getId()] = $item->getUsername();
        }
        $this->addColumn(
            'user_name',
            array(
                'header' => Mage::helper('salesman')->__('USER NAME'),
                'align' => 'left',
                'index' => 'user_id',
                'type' => 'options',
                'options' => $userNames
            )
        );
        $this->addColumn('metric', array(
            'header' => Mage::helper('salesman')->__('METRIC'),
            'align' => 'left',
            'index' => 'metric'
        )
        );
        $this->addColumn('percentage', array(
            'header' => Mage::helper('salesman')->__('PERCENTAGE'),
            'align' => 'left',
            'index' => 'percentage'
        )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('salesman')->__('CREATED AT'),
                'align' => 'left',
                'index' => 'created_at'
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('salesman')->__('UPDATED AT'),
                'align' => 'left',
                'index' => 'updated_at'
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }

}