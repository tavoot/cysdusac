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
 * Description of LoginForm
 *
 * @author nando
 */
class LoginForm extends Form{
    //put your code here
    
    public function __construct(InputFilterInterface $inputFilter) {
        parent::__construct("Ingreso");
        
        $this->setInputFilter($inputFilter);
        
        $this->setAttribute("action", "login");
        $this->setAttribute("class", "col-md-4");
        
        $this->add(array(
            'name' => 'usuario',
            'options' => array(
                'label' => 'Usuario',
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Usuario',
                'class' => 'form-control',
                'autofocus' => 'true',
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'placeholder' => 'Password',
                'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'ingresar',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Ingresar',
                'id' => 'btn_submit',
                'class' => 'btn btn-primary',
            ),
        ));
        
    }
}
