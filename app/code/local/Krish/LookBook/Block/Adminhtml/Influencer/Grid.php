<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Grid extends Mage_Adminhtml_Block_Widget_Grid 
{
    /**
     * Initialize grid, set defaults
     *
     */
    public function __construct() 
    {
        parent::__construct();
        $this->setId('influencerGrid');
        $this->setDefaultSort('influencer_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

     /**
     * Set influencer collection to grid data
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('lookbook/influencer')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * Create grid columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {

        $this->addColumn('influencer_id', array(
            'header' => Mage::helper('lookbook')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'influencer_id',
        ));

        $this->addColumn('influencer_name', array(
            'header' => Mage::helper('lookbook')->__('Influencer name'),
            'index' => 'influencer_name',
             'width' => '150px',
        ));
        $this->addColumn('influencer_image',
                array(
                    'header'=> Mage::helper('lookbook')->__('Image'),
                    'width' => '50px',
                    'index' => 'influencer_image',
                    'filter'    => false,
                    'sortable'  => false,
                    'frame_callback' => array($this, 'callbackImage')
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
        $this->addColumn("created_time", array(
            "header" => Mage::helper("lookbook")->__("Created Time"),
            "type" =>   "datetime", 
            "index" =>  "created_time",
        ));
        $this->addColumn("update_time", array(
            "header" => Mage::helper("lookbook")->__("Update Time"),
            "type" =>   "datetime", 
            "index" =>  "update_time",
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('lookbook')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('lookbook')->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass action options for this grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('influencer_id');
        $this->getMassactionBlock()->setFormFieldName('influencer');

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
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     *  Image SRC
     *
     * @return imageSrc
     */
    public function callbackImage($value)
    {
        $width = 50;
        $height = 50;
        if($value){
            return "<img src=".$value." width=".$width." height=".$height."/>";    
        }else{
            return;
        }
    }    
    

}