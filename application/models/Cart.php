<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Cart {
    
    protected $_articlelist;
    
    public function __construct() {
        $cartNs = new Zend_Session_Namespace('cart');
        foreach($cartNs['article'] as $article) {
            
        }
    }
    
    public function __destruct() {
        ;
    }
    
    public function addProduct() {
        
    }
    
    public function removeProduct() {
        
    }
}