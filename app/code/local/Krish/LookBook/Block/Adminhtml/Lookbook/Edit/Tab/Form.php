<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form
     *
     */
    protected function _prepareForm() 
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('lookbook_form', array('legend' => Mage::helper('lookbook')->__('Item information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('lookbook')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
        $fieldset->addField('stores', 'hidden', array(
            'name' => 'stores[]',
            'value' => Mage::app()->getStore(true)->getId()
        ));

        $fieldset->addField('image', 'image', array(
           'label' => Mage::helper('lookbook')->__('Image'),
           'required' => false,
           'name' => 'image',
        ));
        $fieldset->addField('youtubeid', 'text', array(
            'name' => 'youtubeid',
            'label' => Mage::helper('lookbook')->__('YouTube Video ID'),
            'title' => Mage::helper('lookbook')->__('YouTube Video ID'),
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('lookbook')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('lookbook')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('lookbook')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::getSingleton('adminhtml/session')->getLookBookData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getLookBookData());
            Mage::getSingleton('adminhtml/session')->setLookBookData(null);
        } elseif (Mage::registry('lookbook_data')) {
            $form->setValues(Mage::registry('lookbook_data')->getData());
        }
        return parent::_prepareForm();
    }

}