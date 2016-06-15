<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of UpdatePassForm
 *
 * @author nando
 */
class UpdatePassForm extends Form {
    //put your code here
    
    public function __construct(InputFilterInterface $inputFilter) {
        parent::__construct('updatepass');
        
        $this->setInputFilter($inputFilter);
        
        $this->setAttribute("class", "col-md-4");
        
        $this->add(array(
            'name' => 'pass_actual',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password actual',
            ),
            'attributes' => array(
                'placeholder' => 'Password...',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'pass_nuevo',
            'type' => 'Password',
            'options' => array(
                'label' => 'Nuevo password',
            ),
            'attributes' => array(
                'placeholder' => 'Nuevo password...',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'pass_confirmado',
            'type' => 'Password',
            'options' => array(
                'label' => 'Confirmar password',
            ),
            'attributes' => array(
                'placeholder' => 'Confirme password...',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
           'name' => 'submit',
           'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-success',
                'value' => 'Aceptar',
                'id'    => 'submitbutton',
            ),
        ));
        
        $this->add(array(
           'name' => 'submit',
           'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-danger',
                'value' => 'Cancelar',
            ),
        ));
        
        
    }
}
