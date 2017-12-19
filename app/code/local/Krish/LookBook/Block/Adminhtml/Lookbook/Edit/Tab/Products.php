<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid 
{
    /**
     * Set grid params
     *
     */
    public function __construct() 
    {

        parent::__construct();
        $this->setId('lookbook_products_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');

        $this->setUseAjax(true);
        if ((int) $this->getRequest()->getParam('id', 0)) {
            $this->setDefaultFilter(array('in_products' => 1));
        }
    }
    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection() 
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('attribute_set_id')
                ->addAttributeToSelect('type_id')
                ->addAttributeToSelect('status');

        if ((int) $this->getRequest()->getParam('id', 0)) {
             $collection->getSelect()->joinLeft(array('lookbook' => 'krish_lookbook_products'), 'entity_id=product_id and (lookbook.lookbook_id =' . $this->getRequest()->getParam('id') . ' or lookbook.lookbook_id is null)', 'sort_order');
        }
        $collection->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED));
        $collection->addFieldToFilter('visibility',array('neq'=>'1'));
        $collection->addFieldToFilter('type_id',array('in' => array('simple')));
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('in_products', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_products',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id'
        ));
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id',
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
        ));
        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name', array(
                'header' => Mage::helper('catalog')->__('Name in %s', $store->getName()),
                'index' => 'custom_name',
            ));
        }


        $types = array();
        foreach (Mage::getSingleton('catalog/product_type')->getOptionArray() as $k => $v) {
            if ($k == 'simple') $types[$k] = $v;
            if ($k == 'configurable') $types[$k] = $v;
        }
        
        $this->addColumn('type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => $types,
        ));
        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

        $this->addColumn('set_name', array(
            'header' => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width' => 130,
            'index' => 'attribute_set_id',
            'type' => 'options',
            'options' => $sets,
        ));
        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80px',
            'index' => 'sku',
        ));
        $store = $this->_getStore();
        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
        ));
        $this->addColumn('mstatus', array(
            'header' => Mage::helper('catalog')->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));
        $this->addColumn('sort_order', array(
            'header' => Mage::helper('catalog')->__('Sort Order'),
            'name' => 'sort_order',
            'width' => 60,
            'type' => 'number',
            'validate_class' => 'validate-number',
            'index' => 'sort_order',
            'editable' => true,
            'edit_only' => true
        ));
        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/productsGrid', array('_current' => true));
    }

    /**
     * Add filter
     *
     * @param object $column
     * @return Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Tab_Products
     */
    protected function _addColumnFilterToCollection($column) 
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Retrieve selected lookbook products
     *
     * @return array
     */
    protected function _getSelectedProducts() 
    {
        $products = $this->getRequest()->getPost('products_prodlist', null);
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedProducts());
        }
        return $products;
    }

    /**
     * Retrieve lookbook products
     *
     * @return array
     */
    public function getSelectedProducts() 
    {
        $products = array();

        $sId = Mage::app()->getFrontController()->getRequest()->getParam('id');
        if (isset($sId)) {
            $model = Mage::getModel('lookbook/lookbook')->load($sId);
            $products = $model->getLookbookProducts();
        } 
        return $products;
    }

}


