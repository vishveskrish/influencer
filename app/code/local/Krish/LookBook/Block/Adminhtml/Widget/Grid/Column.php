<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Krish_LookBook_Block_Adminhtml_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column 
{

    protected function _getRendererByType() 
    {
        switch (strtolower($this->getType())) {
            case 'lookbook':
                $rendererClass = 'lookbook/adminhtml_widget_grid_column_renderer_lookbook';
                break;
            default:
                $rendererClass = parent::_getRendererByType();
                break;
        }
        return $rendererClass;
    }

    protected function _getFilterByType() 
    {
        switch (strtolower($this->getType())) {
            case 'lookbook':
                $filterClass = 'lookbook/adminhtml_widget_grid_column_filter_lookbook';
                break;
            default:
                $filterClass = parent::_getFilterByType();
                break;
        }
        return $filterClass;
    }

}