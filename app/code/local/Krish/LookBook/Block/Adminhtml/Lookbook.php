<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
class Krish_LookBook_Block_Adminhtml_Lookbook extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_lookbook';
    $this->_blockGroup = 'lookbook';
    $this->_headerText = Mage::helper('lookbook')->__('Lookbook Manager');
    $this->_addButtonLabel = Mage::helper('lookbook')->__('Add Lookbook');
    parent::__construct();
  }

}