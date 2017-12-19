<?php
/**
 * Krishtechnolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2010-2011 Krishtechnolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit_Tab_Lookbook extends Krish_LookBook_Block_Adminhtml_Widget_Grid 
{
    /**
     * Set grid params
     *
     */
    public function __construct() {
        parent::__construct();
        $this->setId('lookbookLeftGrid');
        $this->setDefaultSort('lookbook_id');
        $this->setUseAjax(true);
    }

    public function getInfluencerData() {       
        return Mage::registry('influencer_data');
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('lookbook/lookbook')->getCollection()
                        ->addFieldToFilter('status',1);
        $collection->getSelect()->order('lookbook_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * Add filter
     *
     * @param object $column
     * @return Krish_LookBook_Block_Adminhtml_Influencer_Edit_Tab_Lookbook
     */
    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'lookbook_triggers') {
                $lookbookIds = $this->_getSelectedLookbooks();
                if (empty($lookbookIds)) {
                    $lookbookIds = 0;
                }
                if ($column->getFilter()->getValue()) {
                    $this->getCollection()->addFieldToFilter('lookbook_id', array('in' => $lookbookIds));
                } else {
                    if ($lookbookIds) {
                        $this->getCollection()->addFieldToFilter('lookbook_id', array('nin' => $lookbookIds));
                    }
                }
            } else {
                parent::_addColumnFilterToCollection($column);
            }
        }
        return $this;;
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {

        $this->addColumn('lookbook_triggers', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'values' => $this->_getSelectedLookbooks(),
            'align' => 'center',
            'index' => 'lookbook_id'
        ));
        $this->addColumn('lookbook_id', array(
            'header' => Mage::helper('lookbook')->__('ID'),
            'sortable' => true,
            'width' => '50',
            'align' => 'center',
            'index' => 'lookbook_id'
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('lookbook')->__('Title'),
            'index' => 'title',
            'align' => 'left',
        ));
        $this->addColumn('image',
            array(
                'header'=> Mage::helper('catalog')->__('Image'),
                'width' => '50px',
                'index' => 'image',
                'filter'    => false,
                'sortable'  => false,
                'frame_callback' => array($this, 'callbackImage')
        ));
      
        $this->addColumn('youtubeid',
            array(
            'header'=> Mage::helper('catalog')->__('Youtube ID'),
            'width' => '50px',
            'index' => 'youtubeid',
            'frame_callback' => array($this, 'callbackVideoImage'),
             'filter'    => false,
            'sortable'  => false
        ));     
         
        $this->addColumn('pstatus', array(
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

      
        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/lookbookgrid', array('_current' => true));
    }

    /**
     * Retrieve selected lookbooks
     *
     * @return array
     */
    protected function _getSelectedLookbooks() 
    {
        $lookbooks = $this->getRequest()->getPost('selected_lookbooks');
        $sId = Mage::app()->getFrontController()->getRequest()->getParam('id');

       
        if (is_null($lookbooks)) {
             $model = Mage::getModel('lookbook/influencer')->load($sId);
             $ids = $model->getData('lookbook_ids');
             $lookbooks = explode(',', $ids);
            return (sizeof($lookbooks) > 0 ? $lookbooks : 0);
        }
        return $lookbooks;
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
        if($value)
            {
                return "<img src=".$value." width=".$width." height=".$height."/>";    
            }
            else
            {
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
        $width = 50;
        $height = 50;
        $src = "https://img.youtube.com/vi/".$value."/1.jpg";
        if($value)
        {
            return "<img src=".$src." width=".$width." height=".$height."/>";
        }
        else
        {
               return;
        }
    }

}
