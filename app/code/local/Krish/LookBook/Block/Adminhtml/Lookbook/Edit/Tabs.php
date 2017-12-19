<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  /**
   * Constructor
   */
  public function __construct()
  {
      parent::__construct();
      $this->setId('lookbook_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('lookbook')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('lookbook')->__('Item Information'),
          'title'     => Mage::helper('lookbook')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('lookbook/adminhtml_lookbook_edit_tab_form')->toHtml(),
      ));
      $this->addTab('products', array(
            'label' => Mage::helper('lookbook')->__('Products'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax',
        ));
      return parent::_beforeToHtml();
  }
}