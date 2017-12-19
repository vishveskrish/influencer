<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs 
{
  /**
   * Constructor
   */
    public function __construct() {
        parent::__construct();
        $this->setId('influencer_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('lookbook')->__('Influncer Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('lookbook')->__('Influencer General'),
            'alt' => Mage::helper('lookbook')->__('Influencer General'),
            'content' => $this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit_tab_form')->toHtml(),
        ));

        $this->addTab('grid_section', array(
            'label' => Mage::helper('lookbook')->__('Associated Lookbooks'),
            'alt' => Mage::helper('lookbook')->__('Associated Lookbooks'),
             'content' => $this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit_tab_gridlookbook')->toHtml(),
            
        ));

        return parent::_beforeToHtml();
    }

}