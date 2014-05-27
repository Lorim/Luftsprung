<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout()->getView()->headTitle("Luftsprung", 
                    Zend_View_Helper_Placeholder_Container_Abstract::SET);
        $layout = $this->_helper->layout()->ogurl = 
                $this->getRequest()->getScheme() . 
                '://' . 
                $this->getRequest()->getHttpHost() .
                $this->getRequest()->getRequestUri();
        $this->_helper->layout()->aPreload = array();
    }

    public function indexAction() {
        
    }
    
    
    public function galleryAction() {

        $page = $this->view->navigation()->findOneBy('active', 2);
        $this->view->label = $page;

        $oGallery = new Application_Model_GalleryMapper();
        $iGalleryid = $this->_request->getParam('id');
        $sGalleryTag = $this->_request->getParam('tag');
        if($iGalleryid) {
            $this->_helper->viewRenderer->setRender('galleryid');
            $oGalleryEntry = $oGallery->find($iGalleryid, new Application_Model_Gallery);
            $this->view->entry = $oGalleryEntry;
            $this->_helper->layout()->getView()->headTitle($oGalleryEntry->getTitle(), 
                Zend_View_Helper_Placeholder_Container_Abstract::SET);
        } else {
            $this->view->entry = $oGallery->findGalleries($sGalleryTag);
        }
    }


    public function loginAction() {
        $form = new Application_Form_Login();
        $request = $this->getRequest();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $auth = Zend_Auth::getInstance();

                $result = $auth->authenticate(new Application_Auth_Adapter($form->getValue('name'), $form->getValue('password')));
                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        echo 'user name is unvalid';
                        break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        echo 'unvalid password provided';
                        break;
                    default:
                        $this->_helper->redirector('index', 'index', 'default');
                        break;
                }
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $this->_helper->redirector->gotoUrl($this->getRequest()->getServer('HTTP_REFERER'));
    }

    public function kontaktAction() {
        $form = new Application_Form_Kontakt();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($form->isValid($request->getParams())) {
                //$form->reset();
                $view = new Zend_View();
                $view->setScriptPath(APPLICATION_PATH . "/views/scripts/mail");
                $view->form = $form->getValues();
                $html = $view->render('kontakt.phtml');
                $mail = new Zend_Mail('UTF-8');
                $mail->setFrom("gallery@se519.de")
                        ->addTo("steffen@se519.de")
                        ->setSubject('Neue Kontaktanfrage von ' . $form->getValue('name'));
                $mail->setBodyHtml($html);
                $fm = new Zend_Controller_Action_Helper_FlashMessenger();
                try {
                    $mail->send();
                    $fm->addMessage('Deine Mail wurde erfolgreich versendet');
                } catch (exception $ex) {
                    $fm->addMessage('Es gab ein Problem beim versenden der Mail.<br>Bitte versuch es später noch einmal.');
                }
            } else {
                $fm = new Zend_Controller_Action_Helper_FlashMessenger();
                $fm->addMessage('In deinen Daten steckt noch ein Fehler. <br>Kontrolliere das rot markierte Feld, korrigiere es und versuchs nochmal.');
            }
            $this->view->commentsubmit = true;
        }

        $this->view->form = $form;
    }
    public function impressumAction() {
        
    }
}
