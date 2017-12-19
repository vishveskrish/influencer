<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Mysql4_Lookbook_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Init lookbook resource model
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('lookbook/lookbook');
    }
     /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Krish_LookBook_Model_Mysql4_Lookbook_Collection
     */
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->join(
                    array('store_table' => $this->getTable('lookbook/lookbook_stores')),
                    'main_table.lookbook_id = store_table.lookbook_id',
                    array()
                    )
                    ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }
  
}