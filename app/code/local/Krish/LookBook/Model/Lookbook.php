<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Lookbook extends Mage_Core_Model_Abstract 
{

    /**
     * Initialize lookbook model
     */
    public function _construct() {
        parent::_construct();
        $this->_init('lookbook/lookbook');
    }

    /**
     * Retrieve array of lookbook products
     *
     * @return array
     */
    public function getLookbookProducts() {
        $collection = Mage::getResourceModel('lookbook/lookbook_products_collection')
                ->addFieldToFilter('lookbook_id', $this->getId())
                ->setOrder('sort_order', 'ASC');
        $result = array();
        foreach ($collection as $value) {
            $result[$value['product_id']] = array('sort_order' => $value['sort_order']);
        }
        return $result;
    }

    /**
     * Retrieve array of lookbook product Ids
     *
     * @return array
     */
    public function getLookbookProductIds() 
    {
        $collection = Mage::getResourceModel('lookbook/lookbook_products_collection')
                ->addFieldToFilter('lookbook_id', $this->getId())
                ->setOrder('sort_order','asc');
        $collection->getSelect()->limit(6);
        $result = array();
        foreach ($collection as $value) {
            $result[] = $value['product_id'];
        }
        return $result;
    }

    /**
     * Retrieve array of lookbook products
     *
     * @return array
     */
    public function getProducts() {

        $collection = Mage::getResourceModel('lookbook/lookbook_products_collection')
                ->addFieldToFilter('lookbook_id', $this->getId())
                ->setOrder('sort_order','asc');
        $collection->getSelect()->limit(6);
        $result = array();
        foreach ($collection as $product) {
            $result[] = Mage::getModel('catalog/product')->load($product['product_id']);
        }
        return $result;
    }    
  
}