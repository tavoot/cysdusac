<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;

/**
 * Description of UploadForm
 *
 * @author nando
 */
class UploadForm extends Form {
    //put your code here
    
    public function __construct($name = null) {
        
        parent::__construct('upload');
        
        
        $this->add(array(
            'name' => 'input_carga',
            'type' => 'File',
            'options' => array(
                'label' => 'Imagen',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
           'name' => 'submit',
           'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-success',
                'value' => 'Cargar',
                'id'    => 'submitbutton',
            ),
        ));
    }
}
