<?php
/**
 * Krishtechnolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2010-2011 Krishtechnolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Influencer_Edit_Tab_Gridlookbook extends Mage_Adminhtml_Block_Widget_Container {

    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('krish_lookbook/lookbooks.phtml');
        
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    /**
     * Prepare button and grid
     *
     */
    protected function _prepareLayout() {
        $this->setChild('tabs', $this->getLayout()->createBlock('lookbook/adminhtml_influencer_edit_tab_lookbook', 'influencer.grid.lookbook'));

        return parent::_prepareLayout();
    }
    
    public function getInfluencerData() {
        return Mage::registry('influencer_data');
    }

    public function getLookbooksJson() {
      
        $lookbooks = explode(',', $this->getInfluencerData()->getLookbookIds());
        if (!empty($lookbooks) && isset($lookbooks[0]) && $lookbooks[0] != '') {
            $data = array();
            foreach ($lookbooks as $element) {
                $data[$element] = $element;
            }
            return Zend_Json::encode($data);
        }
        return '{}';
    }

}
