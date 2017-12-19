<?php
/**
 * Krish Technolabs
 * 
 * @package    Krish_LookBook
 * @copyright  Copyright (c) 2017 Krish Technolabs. (http://www.krishtechnolabs.com)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Krish_LookBook_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Renders Influencer Page Can be used for the future individual page.
     * 
     */
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Retrive GTM lookbook post data 
     * 
     */
    public function gtmsendAction() 
    {
        $post = $this->getRequest()->getPost();
         if ( $post['lookbookid'] ) {
            $lookbook =  Mage::getModel('lookbook/lookbook')->load($post['lookbookid']);
            Mage::register('lookbookdata', $lookbook);
            $influecnerCategory =  Mage::getModel('catalog/category')->load($post['catId']);
             Mage::register('influecner_category', $influecnerCategory);
            $this->loadLayout();
            $this->renderLayout();
        }
    }

    /**
     * Add All Basket Products 
     * 
     */
    public function addmultipleAction() {
       $post = $this->getRequest()->getPost();
       $sgthelper = Mage::helper('scommerce_googletagmanagerpro');
        if ( $post['productadddata'] ) {
            try{
                    $validProducts = explode(',', $post['productadddata']);
                    $cart = Mage::helper('checkout/cart')->getCart();
                    $msg="All Products are successfully added into cart";
                      $productToBasketMulti = array();
                        foreach($validProducts as $productId) {
                        $param = array(
                                'product' => $productId,
                                'qty' => 1
                             );
                            $request = new Varien_Object();
                            $request->setData($param);

                            $product =  Mage::getModel('catalog/product')
                                        ->setStoreId(Mage::app()->getStore()->getId())
                                        ->load($productId);
                          

                            $result = $cart->addProduct($product, $param);
                             if ($productId != end($validProducts)){
                                $productToBasketMulti[] = array(
                                        'id' => $product->getSku(),
                                        'name' => $product->getName(),
                                        'category' => $sgthelper->getProductCategoryName($product),
                                        'brand' => $sgthelper->getBrand($product),
                                        'price' => $product->getPrice(),
                                        'qty' => ($product->getQty()<=0)? 1 : $product->getQty()
                                    );
                            }
                            $msg .= $product->getName(). " is successfully added into cart<br>";
                        }

                        $cart->save();
                        Mage::getSingleton('core/session')->addSuccess(Mage::helper('checkout')->__($msg));
                        $cookie = Mage::getSingleton('core/cookie');
                        $cookie->set('productToBasketMulti', json_encode($productToBasketMulti), time() + 86400, '/', null, null, false);
                        $this->_redirect('checkout/cart');
                    }
                    catch(Exception $e) {
                    Mage::getSingleton('core/session')->addError(Mage::helper('checkout')->__($e->getMessage()));
                    $this->_redirect('checkout/cart');
                 }
         }
    }
    
}