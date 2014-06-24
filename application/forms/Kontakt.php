<?php

class Application_Form_Kontakt extends Zend_Form {

    public function init() {
        // Setzt die Methode for das Anzeigen des Formulars mit POST
        $this->setMethod('post');

        // Ein Email Element hinzufügen
        $this->addElement('text', 'name', array(
            'label' => 'Dein Name:',
            'required' => true,
            'filters' => array('StringTrim'),
        ));
        $this->addElement('text', 'mail', array(
            'label' => 'Deine Mail Adresse:',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('validator' => 'EmailAddress', null)
            )
        ));
        // Das Kommentar Element hinzufügen
        $this->addElement('textarea', 'message', array(
            'label' => 'Bitte ein Kommentar:',
            'required' => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 2000))
            )
        ));

        $this->addElement('select', 'subject', array(
            'label' => 'Betreff',
            'required' => true,
            'multiOptions' => array(
                'service' => 'Allgemeine Fragen',
                'shooting' => 'Shooting',
                'error' => 'Fehler auf der Seite'
            )
        ));
        
        // Den Submit Button hinzufügen
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Absenden',
        ));

        /*
          // Und letztendlich etwas CSRF Protektion hinzufügen
          $this->addElement('hash', 'csrf', array(
          'ignore' => true,
          ));
         */
    }

}
