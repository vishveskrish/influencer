<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form 
{
    /**
     * Check wysiwyg is enable or not
     *
     */    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
    }

    /**
     * Prepare form
     *
     */
    protected function _prepareForm() 
    {

        $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
            'add_widgets' => false,
            'add_variables' => false,
            'add_images' => true,
            'files_browser_window_url'=> $this->getBaseUrl().'admin/cms_wysiwyg_images/index/',
            ));
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('influencer_form',
             array('legend' => Mage::helper('lookbook')->__('Item information')));

        $fieldset->addField('influencer_name', 'text', array(
            'label' => Mage::helper('lookbook')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'influencer_name'
        ));
        $fieldset->addField('influencer_image', 'image', array(
           'label' => Mage::helper('lookbook')->__('Image'),
           'name' => 'influencer_image'
           
        ));
        $fieldset->addField('influencer_bio', 'editor', array(
            'name'      => 'influencer_bio',
            'label'     => Mage::helper('lookbook')->__('Bio'),
            'title'     => Mage::helper('lookbook')->__('Bio'),
            'style' => 'width:400px; height:200px;',
            'required'  => true,
            'wysiwyg' => true,
            'required' => true,
            'config' => $config
          
        ));
        $fieldset->addField('influencer_tagline', 'editor', array(
            'name'      => 'influencer_tagline',
            'label'     => Mage::helper('lookbook')->__('Influencer Tagline'),
            'title'     => Mage::helper('lookbook')->__('Influencer Tagline'),
            'style' => 'width:400px; height:200px;',
            'required'  => true,
            'wysiwyg' => true,
            'required' => true,
            'config' => $config
        ));
        $fieldset->addField('influencer_code', 'editor', array(
            'name'      => 'influencer_code',
            'label'     => Mage::helper('lookbook')->__('Offers'),
            'title'     => Mage::helper('lookbook')->__('Offers'),
            'style' => 'width:400px; height:200px;',
            'required'  => true,
            'wysiwyg' => true,
            'required' => true,
            'config' => $config
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('lookbook')->__('Status'),
            'name' => 'status',
             'required' => true,
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

        if (Mage::getSingleton('adminhtml/session')->getInfluencerData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getInfluencerData());
            Mage::getSingleton('adminhtml/session')->getInfluencerData(null);
        } elseif (Mage::registry('influencer_data')) {
            $form->setValues(Mage::registry('influencer_data')->getData());
        }
        return parent::_prepareForm();
    }

}