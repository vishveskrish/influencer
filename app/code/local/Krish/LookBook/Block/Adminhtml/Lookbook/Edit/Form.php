<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
 
  /**
   * Preparing block layout
   *
   * @return Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Form
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
   * Prepare form. 
   *
   * @return Krish_LookBook_Block_Adminhtml_Lookbook_Edit_Form
   */
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
        							                'enctype' => 'multipart/form-data'
                                   )
      );

      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}