<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/datatables.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-editable.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-datepicker.de.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/bootstrap-select.min.js'));
        $this->view->headScript()->appendFile( $this->view->baseUrl('/js/site.admin.js'));
        
        $this->view->headLink()->appendStylesheet( $this->view->baseUrl('/css/bootstrap-editable.css'));
        $this->view->headLink()->appendStylesheet( $this->view->baseUrl('/css/bootstrap-select.min.css'));
        $this->view->headLink()->appendStylesheet( $this->view->baseUrl('/css/datatables.css'));
    }

    public function commentsAction()
    {
    	$guestbook = new Application_Model_CommentMapper();
    	$this->view->entries = $guestbook->fetchAll();
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
            $oEntry->setPath($this->_request->getParam('path'));
            $oEntry->setTag($this->_request->getParam('tag'));
            $oEntry->setActive($this->_request->getParam('active', 0));
            $oNews->save($oEntry);
            $fm = new Zend_Controller_Action_Helper_FlashMessenger();
            $fm->addMessage('Die Gallerie wurde erstellt :) <br> Fehlen noch die Vorschaubilder.');
        }  catch (Exception $e) {
            echo $this->view->json(array("success" => false));
        }
        echo $this->view->json(array("success" => true, "id" => 1));
    }
}

