<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Mysql4_Lookbook extends Mage_Core_Model_Mysql4_Abstract 
{

    /**
     * Initialize lookbook data
     *
     * @return null
     */
    public function _construct() {
        // Note that the lookbook_id refers to the key field in your database table.
        $this->_init('lookbook/lookbook', 'lookbook_id');
    }

    /**
     * Save lookbook content, bind products to lookbook after lookbook save
     *
     * @return Krish_LookBook_Model_Mysql4_Lookbook
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        if ($object->getData('links')) {

            foreach (Mage::getResourceModel('lookbook/lookbook_products_collection')
                    ->addFieldToFilter('lookbook_id', $object->getId()) as $product) {
                $product->delete();
            }
            $data = $object->getData('links');

            $link_Data = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['prodlist']);

            foreach ($link_Data as $key => $value) {
                $prodlistModel = Mage::getModel('lookbook/lookbook_products');
                $prodlistModel->setLookbookId($object->getId())
                        ->setProductId($key)
                        ->setSortOrder($value['sort_order']);

                $prodlistModel->save();
            }
        }
       
         $this->__saveToStoreTable($object);
        return parent::_afterSave($object);
    }

    
    /**
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        if (!$object->getIsMassDelete()) {
            $object = $this->__loadStore($object);
           
        }

        return parent::_afterLoad($object);
    }
  
    /**
     * Save stores
     */
    private function __saveToStoreTable(Mage_Core_Model_Abstract $object) {
        if (!$object->getData('stores')) {
            $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);

            $storeArray = array(
                'lookbook_id' => $object->getId(),
                'store_id' => '0');
            $this->_getWriteAdapter()->insert(
                    $this->getTable('lookbook/lookbook_stores'), $storeArray);
            return true;
        }

        $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);
        foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array(
                'lookbook_id' => $object->getId(),
                'store_id' => $store);
            $this->_getWriteAdapter()->insert(
                    $this->getTable('lookbook/lookbook_stores'), $storeArray);
        }
    }
    /**
     * Load stores
     */
    private function __loadStore(Mage_Core_Model_Abstract $object) {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('lookbook/lookbook_stores'))
                ->where('lookbook_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $array = array();
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('store_id', $array);
        }
        return $object;
    }

     /**
     * Clear data from the lookbook stores and lookbook products before deleting the lookbook
     *
     * @return Krish_LookBook_Model_Lookbook
     */   
    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        
        $condition = $this->_getWriteAdapter()->quoteInto('lookbook_id = ?', $object->getId());
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_stores'), $condition);           
            $this->_getWriteAdapter()->delete($this->getTable('lookbook/lookbook_products'), $condition);       
    }
}