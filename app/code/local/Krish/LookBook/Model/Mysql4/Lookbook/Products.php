<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Mysql4_Lookbook_Products extends Mage_Core_Model_Mysql4_Abstract
{	
	/**
     * Init lookbook Products resource model
     *
     */
    public function _construct() {
        // Note that the lookbook_id refers to the key field in your database table.
        $this->_init('lookbook/lookbook_products', 'lookbook_entity_id');
    }
}

