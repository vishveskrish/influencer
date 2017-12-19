<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Influencer extends Mage_Catalog_Block_Product_Abstract
{

    /**
     * Prepare global layout
     *
     * @return Krish_LookBook_Block_Influencer
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    /**
     * Retrieve Influencer instance
     *
     * @return Krish_LookBook_Model_Influencer
     */
    public function getInfluencer()     
    { 
        if (!$this->hasData('influencer')) {
            $this->setData('influencer', Mage::registry('influencer'));
        }
        return $this->getData('influencer');
    }

    /**
     * Retrieve Category based Influencer instance
     *
     * @return Krish_LookBook_Model_Influencer
     */

    public function getCategoryInfluencer()     
    { 
        $influencer = Mage::registry('influencer');
        return $influencer;
    }


    /**
     * Retrieve All the lookbook ids for the influencer
     *
     * @return array of lookbookids
     */

    public function getInfluencerLookbookIds()     
    { 
        $influencer = $this->getCategoryInfluencer();
        $lookbookIds = $influencer->getLookbookIds();
        return $lookbookIds;
    }

    /**
     * Retrieve All the lookbook for the influencer
     *
     * @return Krish_LookBook_Model_LookBook
     */
    public function getInfluencerLookbooks()     
    { 
        $ids = $this->getInfluencerLookbookIds();
        $idsArray = explode(",",$ids);
        $lookbooks =  array();
         foreach ($idsArray as $id){
            $lookbooks[] = 
                array('finset'=> array($id));   
         }
         $lookbook = Mage::getModel('lookbook/lookbook')->getCollection()
                          ->addFieldToFilter('lookbook_id', $lookbooks);
    	return $lookbook;
    }


}
