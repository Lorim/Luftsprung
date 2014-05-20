<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JsonController extends Zend_Controller_Action {
    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function galleriesAction() {
        $this->getResponse()
         ->setHeader('Content-Type', 'application/json');
        $aReturn = array();
        $navigation = Zend_Registry::get('nav');
        foreach($navigation as $gallery) {
            $aEntry = array();
            if($gallery->getId() == 'gallery') {
                $aEntry['text'] = $gallery->getLabel();
                if($gallery->hasPages()) {
                    foreach($gallery->getPages() as $oPage) {
                        $aSubentry = array();
                        $aSubentry['text'] = $oPage->getLabel();
                    }
                    $aEntry['nodes'] = $aSubentry;
                }
                
                $aReturn[] = $aEntry;
            }
        }
        
        echo json_encode($aReturn);
        /*
         * 
         * var tree = [
            {
                text: "Parent 1",
                nodes: [
                    {
                        text: "Child 1",
                        nodes: [
                            {
                                text: "Grandchild 1"
                            },
                            {
                                text: "Grandchild 2"
                            }
                        ]
                    },
                    {
                        text: "Child 2"
                    }
                ]
            }
        ];
         */
    }
}