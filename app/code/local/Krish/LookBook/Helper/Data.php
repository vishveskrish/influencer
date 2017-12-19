<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_Helper_Data extends Mage_Core_Helper_Abstract 
{
        /**
         * Return url to add multiple items to the cart
         * @return  url
         */
        public function getAddToCartUrl()
        {
            if ($currentCategory = Mage::registry('current_category')) {
                $continueShoppingUrl = $currentCategory->getUrl();
            } else {
                $continueShoppingUrl = $this->_getUrl('*/*/*', array('_current'=>true));
            }

            $params = array(
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => Mage::helper('core')->urlEncode($continueShoppingUrl)
            );

            if ($this->_getRequest()->getModuleName() == 'checkout'
                && $this->_getRequest()->getControllerName() == 'cart') {
                $params['in_cart'] = 1;
            }
            return $this->_getUrl('lookbook/index/addmultiple', $params);
        }

        /**
         * Return starrating
         * @return  starrating
         */
        public function getStarRating($value=null)
        {
            if(!$value){
               return false;
            }
            switch ($value) {
                case "1":
                    return '20%';
                    break;
                case "2":
                    return '40%';
                    break;
                case "3":
                    return '60%';
                    break;
                case "4":
                    return '80%';
                    break;    
                 case "5":
                    return '100%';
                    break;   
                case "0.5":
                    return '9%';
                    break;
                case "1.5":
                    return '25%';
                    break;
                case "2.5":
                    return '45%';
                    break;
                case "3.5":
                    return '64%';
                    break;    
                 case "4.5":
                    return '82%';
                    break;              
                default:
                    return false;
            }
            
        }

        /**
         * Return Image path 
         * @return  @imageIcon
         */
        public function getImagePathProduct($value=null)
        {
            if(!$value){
               return false;
            }
             $imageSrc = Mage::getBaseUrl('media').$value;
             return $imageSrc;
        }

        /**
         * Convert string to the url key format
         * @return  string
         */
        public function sanitizeStringForTag($string){
                $string = strtolower($string);
                $string = html_entity_decode($string);
                $string = str_replace(array('ä','ü','ö','ß'),array('ae','ue','oe','ss'),$string);
                $string = preg_replace('#[^\w\säüöß]#',null,$string);
                $string = preg_replace('#[\s]{2,}#',' ',$string);
                $string = str_replace(array(' '),array('-'),$string);
                return $string;
        }

}