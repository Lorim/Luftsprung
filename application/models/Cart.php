<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Cart {
    
    private $_articlelist;
    
    public function __construct() {
        $cartNs = new Zend_Session_Namespace('cart');
        if(null !== $cartNs->articlelist) {
            $this->_articlelist = $cartNs->articlelist;
        }
    }
    
    public function __destruct() {
        $cartNs = new Zend_Session_Namespace('cart');
        $cartNs->articlelist = $this->_articlelist;
    }
    public function getProducts() {
        return $this->_articlelist;
    }
    public function addProduct($artnr, $count = 1) {
        $idx = $this->findArticle($artnr);
        if($idx !== false) {
            $this->_articlelist[$idx]['count'] += $count;
            return $this;
        }
        $oArticle = new Application_Model_ArticleMapper();
        $aArt = $oArticle->findArticle($artnr);
        $this->_articlelist[] = array(
            'count' => $count,
            'article' => $aArt
        );
        return $this;
    }
    public function removeProduct($artnr) {
        $idx = $this->findArticle($artnr);
        if($idx === false) {
            return $this;
        }
        unset($this->_articlelist[$idx]);
        return $this;
    }
    public function setAmount($artnr, $count) {
        if($idx = $this->findArticle($artnr)) {
            $this->_articlelist[$idx]['count'] = $count;
        }
        return $this;
    }
    
    public function findArticle($artnr) {
        if(!is_array($this->_articlelist)) {
            return false;
        }
        foreach($this->_articlelist as $idx => $article) {
            if($article['article']->getArtnr() == $artnr) {
                return $idx;
            }
        }
        return false;
    }
    
    public function getTotal() {
        $fTotal = 0;
        if(!count($this->_articlelist)) {
            return $fTotal;
        }
        foreach($this->_articlelist as $entry) {
            $fTotal += ($entry['count'] * $entry['article']->getPrice());
        }
        return $fTotal;
    }

}