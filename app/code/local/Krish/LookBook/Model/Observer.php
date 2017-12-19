<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Model_Observer
{
    
    
    
    /**
     * Krish Technolabs
     * Below function will update the templates based on category influencer
     * @package    Krish_LookBook
     * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
     * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
     */
    
    public function AddInfluencerBlock($observer)
    {
        
        $currentCategory = Mage::registry('current_category');
        if (!$currentCategory) {
            return;
        }
        $layout       = $observer->getBlock()->getLayout();
        $block        = $observer->getBlock();
        $influencerId = $currentCategory->getKrishInfluencer();
        if (!isset($influencerId)) {
            return;
        }
        $blockNameInLayout = $block->getNameInLayout();
        if ($blockNameInLayout == 'root') {
            $influencer = Mage::getModel('lookbook/influencer')->load($influencerId);
            Mage::register('influencer', $influencer);
            
            $template = 'krish_lookbook/influencer/category/influencer.phtml';
            if ($this->isMobile()) {
                $template = 'krish_lookbook/influencer/category/mobile/influencer.phtml';
            }
            $customInfluencerBlock = Mage::app()->getLayout()->createBlock('lookbook/influencer', 'lookbook_index_index', array(
                'template' => $template,
                'influencer' => Mage::registry('influencer')
            ));
        
            $block->setChild('influencer', $customInfluencerBlock);
        }
        // if ($blockNameInLayout == 'category.products') {
        //     $block->setTemplate('krish_lookbook/influencer/category/view.phtml');
        // }
        if ($blockNameInLayout == 'googletagmanagerpro_list') {
            $influencergtm = Mage::getModel('lookbook/influencer')->load($influencerId);
            Mage::register('influencergtm', $influencergtm);
            $block->setTemplate('krish_lookbook/influencer/category/gtm/list.phtml');
            $templatenew         = 'krish_lookbook/influencer/category/gtm/influencer-top.phtml';
            $customInfluencergtm = Mage::app()->getLayout()->createBlock('lookbook/influencer_gtm', 'lookbook_index_gtm', array(
                'template' => $templatenew,
                'influencergtm' => Mage::registry('influencergtm')
            ));
            $block->setChild('influencergtm', $customInfluencergtm);
        }
        if ($blockNameInLayout == 'product_list') {
            $block->setTemplate('krish_lookbook/influencer/category/product/list.phtml');
            $block->setColumnCount(2);
        }
        if ($blockNameInLayout == 'product_list_toolbar' && !$this->isMobile()) {
            $block->setTemplate('krish_lookbook/influencer/category/product/toolbar.phtml');
        }
    }
    
    /**
     * Krish Technolabs
     * Check the user agent is mobile 
     * @package    Krish_LookBook
     * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
     * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
     */
    public function isMobile()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/(android|avantgo|blackberry|iPhone|bolt|iPad|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
   
}