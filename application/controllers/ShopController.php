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
        $oArticle = new Application_Model_Article();
    }
}
