<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Mysql4_Influencer extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize influencer model
     */
    protected function _construct()
    {
        $this->_init('lookbook/influencer', 'influencer_id');
    }
}