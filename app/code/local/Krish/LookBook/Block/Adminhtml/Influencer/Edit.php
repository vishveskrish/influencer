<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container 
{
    /**
     * Init class
     *
     */
    public function __construct()
     {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'lookbook';
        $this->_controller = 'adminhtml_influencer';
        $this->_updateButton('save', 'label', Mage::helper('lookbook')->__('Save Influencer'));
        $this->_updateButton('delete', 'label', Mage::helper('lookbook')->__('Delete Influencer'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('lookbook_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'lookbook_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'lookbook_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        
    }

    /**
     * Check wysiwyg is enable or not
     *
     */
    protected function _prepareLayout() 
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) 
        {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }
    
    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText() {
        if (Mage::registry('influencer_data') && Mage::registry('influencer_data')->getId()) {
            return Mage::helper('lookbook')->__("Edit Influencer '%s'", $this->htmlEscape(Mage::registry('influencer_data')->getInfluencerName()));
        } else {
            return Mage::helper('lookbook')->__('Add Influencer');
        }
    }

}