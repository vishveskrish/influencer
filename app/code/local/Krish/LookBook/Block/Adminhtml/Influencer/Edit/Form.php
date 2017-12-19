<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit_Form extends Mage_Adminhtml_Block_Widget_Form 
{
    /**
     * Prepare form
     *
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                        )
        );

        $form->setUseContainer(true);
        $form->addField('in_influencer_lookbook', 'hidden', array(
            'name' => 'influencer_lookbook',
            'required' => false,
        ));
        $this->setForm($form);
        return parent::_prepareForm();
    }

}