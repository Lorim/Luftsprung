<?php

class ShopController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("Luftsprung", 
                    Zend_View_Helper_Placeholder_Container_Abstract::SET);
        $layout = $this->_helper->layout()->ogurl = 
                $this->getRequest()->getScheme() . 
                '://' . 
                $this->getRequest()->getHttpHost() .
                $this->getRequest()->getRequestUri();
        if(null !== $this->_request->getParam('addcart')) {
            $oCart = new Application_Model_Cart();
            $oCart->addProduct(
                    $this->_request->getParam('addcart'), 
                    $this->_request->getParam('qty', 1));
        }
        $oNav = Zend_Registry::get('nav');
        $oNav->addPage(
                new Zend_Config(
                    array(
                        'label' => 'Warenkorb',
                        'controller' => 'shop',
                        'action' => 'cart',
                        'class' => 'pull-right',
                        'route' => 'default'
                    )
                )
            );
    }

    public function indexAction() {
        $oArticle = new Application_Model_ArticleMapper();
        $oArticles = $oArticle->fetchAll();
        $this->view->articles = $oArticles;
    }
    
    public function productAction() {
        $oArticles = new Application_Model_ArticleMapper();
        $oArticle = $oArticles->find($this->_request->getParam('id'), new Application_Model_Article());
        $this->view->article = $oArticle;
    }
    
    public function cartAction() {
        $oCart = new Application_Model_Cart();
        $this->view->cart = $oCart;
        //Zend_Debug::dump($oCart);
    }
}
