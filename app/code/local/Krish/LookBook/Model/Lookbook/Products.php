<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Lookbook_Products extends Mage_Core_Model_Abstract 
{
	/**
     * Init lookbook Products Resource model
     *
     */	
    public function _construct() {
        parent::_construct();
        $this->_init('lookbook/lookbook_products');
    }
    
}


