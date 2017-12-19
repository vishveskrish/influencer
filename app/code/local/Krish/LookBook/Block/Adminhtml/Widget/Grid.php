<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid 
{
    /**
     * Add column to grid
     *
     * @param   string $columnId
     * @param   array || Varien_Object $column
     * @return  Krish_LookBook_Block_Adminhtml_Widget_Grid
     */
    public function addColumn($columnId, $column) {
        if (is_array($column)) {
            $this->_columns[$columnId] = $this->getLayout()->createBlock('lookbook/adminhtml_widget_grid_column')
                            ->setData($column)
                            ->setGrid($this);
        }
        /* elseif ($column instanceof Varien_Object) {
          $this->_columns[$columnId] = $column;
          } */ else {
            throw new Exception(Mage::helper('adminhtml')->__('Wrong column format'));
        }

        $this->_columns[$columnId]->setId($columnId);
        $this->_lastColumnId = $columnId;
        return $this;
    }

}
