<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_influencer';
        $this->_blockGroup = 'lookbook';
        $this->_headerText = Mage::helper('lookbook')->__('Influencer Manager');
        $this->_addButtonLabel = Mage::helper('lookbook')->__('Add Influencer');
        parent::__construct();
    }

}