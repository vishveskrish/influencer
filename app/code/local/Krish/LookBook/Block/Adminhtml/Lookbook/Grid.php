<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Lookbook_Grid extends Mage_Adminhtml_Block_Widget_Grid 
{
    /**
     * Initialize grid, set defaults
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('lookbookGrid');
        $this->setDefaultSort('lookbook_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Set lookbook collection to grid data
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('lookbook/lookbook')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Create grid columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('lookbook_id', array(
            'header' => Mage::helper('lookbook')->__('ID'),
            'align' => 'right',
            'width' => '30px',
            'index' => 'lookbook_id',
        ));
        $this->addColumn('title', array(
            'header' => Mage::helper('lookbook')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'width' => '150px',
        ));
        $this->addColumn('image',
            array(
                'header'=> Mage::helper('catalog')->__('Image'),
                'width' => '70px',
                'index' => 'image',
                'filter'    => false,
                'sortable'  => false,
                'frame_callback' => array($this, 'callbackImage')
        ));
        $this->addColumn('youtubeid',
        array(
            'header'=> Mage::helper('catalog')->__('Youtube ID'),
            'width' => '70px',
            'index' => 'youtubeid',
            'frame_callback' => array($this, 'callbackVideoImage'),
             'filter'    => false,
            'sortable'  => false
        ));     
         
        $this->addColumn('status', array(
            'header' => Mage::helper('lookbook')->__('Status'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('lookbook')->__('Action'),
            'width' => '80px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('lookbook')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('lookbook')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('lookbook')->__('XML'));

        return parent::_prepareColumns();
    }
    /**
     * Prepare mass action options for this grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('lookbook_id');
        $this->getMassactionBlock()->setFormFieldName('lookbook');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('lookbook')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('lookbook')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('lookbook/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('lookbook')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('lookbook')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    /**
     * Ajax grid URL getter
     *
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    /**
     *  Image SRC
     *
     * @return imageSrc
     */
    public function callbackImage($value)
    {
        $width = 70;
        $height = 70;
        if($value){
            return "<img src=".$value." width=".$width." height=".$height."/>";    
        }else{
            return;
        }
        
    }

    /**
     *  Video Thumbnail Image SRC
     *
     * @return imageSrc
     */
    public function callbackVideoImage($value)
    {
        $width = 70;
        $height = 70;
        $src = "https://img.youtube.com/vi/".$value."/hqdefault.jpg";
         if($value){
           return "<img src=".$src." width=".$width." height=".$height."/>";
          }else{
             return;
          }
    }

}