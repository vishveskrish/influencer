<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Mysql4_Influencer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{   
    /**
     * Init influencer resource model
     *
     */
    protected function _construct()
    {
        $this->_init('lookbook/influencer');
    }

    /**
     * Add Filter by status
     *
     * @param int $status
     * @return Image_Collection
     */
    public function addEnableFilter($status = 0) {
        $this->getSelect()->where('main_table.disabled = ?', $status);
        return $this;
    }
}