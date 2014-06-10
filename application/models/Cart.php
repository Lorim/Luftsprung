<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Cart implements Iterator{
    
    private $_articlelist;
    private $_position;
    
    public function __construct() {
        $cartNs = new Zend_Session_Namespace('cart');
        $oArticle = new Application_Model_ArticleMapper();

        if(null !== $cartNs->articles) {
            $this->_articlelist = $cartNs->articlelist;
        }
    }
    
    public function __destruct() {
        $cartNs = new Zend_Session_Namespace('cart');
        $cartNs->articlelist = $this->_articlelist;
    }
    
    public function addProduct($artnr, $count = 1) {
        if(($idx = $this->findArticle($artnr)) !== false) {
            $this->_articlelist[$idx]['count'] = $count;
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
        if(!isset($this->_articlelist[$artnr])) {
            return $this;
        }
        unset($this->_articlelist[$artnr]);
        return $this;
    }
    public function setAmount($artnr, $count) {
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
    
    public function rewind() {
        $this->_position = 0;
    }

    public function valid() {
        return $this->_position < sizeof($this->_articlelist);
    }

    public function key() {
        return $this->_position;
    }

    public function current() {
        return $this->_articlelist[$this->_position];
    }

    public function next() {
        $this->_position++;
    }

}