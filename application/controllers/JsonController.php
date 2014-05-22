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
                        $aSubentry['element'] = $oPage->getParam('tag');
                        $aEntry['nodes'][] = $aSubentry;
                    }
                }
                $aReturn[] = $aEntry;
            }
        }
        echo json_encode($aReturn);
    }
    
    public function loadgallerieAction() {
        $tag = $this->_request->getParam('tag');
        $oGalleries = new Application_Model_GalleryMapper();
    	$this->view->entries = $oGalleries->findGalleries($tag);
        $this->view->tag = $tag;
        $output = $this->view->render('json/gallery.phtml');
        echo $output;
    }
    public function loadgalleriemodalAction() {
        $id = $this->_request->getParam('gallerieid', NULL);
        $oGalleries = new Application_Model_GalleryMapper();
        $this->view->entry = $oGalleries->find($id, new Application_Model_Gallery);
        
        $this->_helper->viewRenderer->setNoRender(false);
    }
}