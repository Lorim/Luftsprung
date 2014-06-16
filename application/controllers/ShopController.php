<?php

class ShopController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("Luftsprung", 
                    Zend_View_Helper_Placeholder_Container_Abstract::SET);
        $fm = new Zend_Controller_Action_Helper_FlashMessenger();
        if(null !== $this->_request->getParam('addcart')) {
            $oCart = new Application_Model_Cart();
            $oCart->addProduct(
                    $this->_request->getParam('addcart'), 
                    $this->_request->getParam('qty', 1));
            $msg = sprintf("%dx Artikel %s in den Warenkorb gelegt.",
                    $this->_request->getParam('qty'),
                    $this->_request->getParam('addcart'));
            $fm->addMessage($msg);
            
        }
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
    }
}
