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
        
    }
}
