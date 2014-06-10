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
        if(null !== $cartNs->articles) {
            foreach($cartNs->articles as $idx => $data) {
                $this->addProduct($data['artnr'], $data['count']);
            }
        }
    }
    
    public function __destruct() {
        $cartNs = new Zend_Session_Namespace('cart');
        foreach($this->_articlelist as $idx => $data) {
            $cartNs->articles[$idx] = $data;
        }
    }
    public function addProduct($artnr, $count) {
        if(($idx = $this->findArticle($artnr)) !== false) {
            $this->_articlelist[$idx]['count'] += $count;
            return $this;
        }
        
        $this->_articlelist[] = array(
            'artnr' => $artnr,
            'count' => $count
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
            if($article['artnr'] == $artnr) {
                return $idx;
            }
        }
        return false;
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