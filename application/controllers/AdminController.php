<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-editable.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-datepicker.de.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-select.min.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/site.admin.js'));
        
        $this->view->headLink()->appendStylesheet( $this->view->baseUrl('/css/bootstrap-editable.css'));
        $this->view->headLink()->appendStylesheet( $this->view->baseUrl('/css/bootstrap-select.min.css'));
    }

    public function commentsAction()
    {
    	$guestbook = new Application_Model_CommentMapper();
    	$this->view->entries = $guestbook->fetchAll();
    }
    
    public function galleryAction()
    {
        
    }
    public function addgalleryAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        try{
            $oNews = new Application_Model_GalleryMapper();
            $oEntry = new Application_Model_Gallery();
            $oEntry->setTitle($this->_request->getParam('title'));
            $oEntry->setCreated($this->_request->getParam('created'));
            $oEntry->setTag($this->_request->getParam('tag'));
            $oEntry->setActive($this->_request->getParam('active', 1));
            $oEntry->setEntry($this->_request->getParam('entry'));
            $oEntry->setId($this->_request->getParam('galleryid'));
            Zend_Debug::dump($oEntry);
            $oNews->save($oEntry);
            $fm = new Zend_Controller_Action_Helper_FlashMessenger();
            //$fm->addMessage('Die Gallerie wurde erstellt :) <br> Fehlen noch die Vorschaubilder.');
        }  catch (Exception $e) {
            echo $this->view->json(array("success" => false));
        }
        echo $this->view->json(array("success" => true, "id" => 1));
    }
    public function deletegalleryAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        try{
            $oGalleries = new Application_Model_GalleryMapper();
            $entry = $oGalleries->find($this->_request->getParam('id'), new Application_Model_Gallery);
            $oGalleries->delete($entry);
        }  catch (Exception $e) {
            echo $this->view->json(array("success" => false));
        }
    }
}

