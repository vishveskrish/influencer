<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_LookBook extends Mage_Core_Block_Template
{

    /**
     * Prepare global layout
     *
     * @return Krish_LookBook_Block_LookBook
     */
	public function _prepareLayout()
    {
	return parent::_prepareLayout();
    }
    

    /**
     * Retrieve lookbooks
     *
     * @return Krish_LookBook_Model_LookBook
     */

    public function getLookBook()     
    { 
        if (!$this->hasData('lookbook')) {
            $this->setData('lookbook', Mage::registry('lookbook'));
        }
        return $this->getData('lookbook');        
    }
}
