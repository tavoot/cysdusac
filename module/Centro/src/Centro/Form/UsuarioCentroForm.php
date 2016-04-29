<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;
use Zend\Form\Element;

 class UsuarioCentroForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('usuario');

          $this->add(array(
             'name' => 'usuario',
             'type' => 'Zend\Form\Element\Select',
             'label' => 'Seleccionar Usuario',
         ));
         
         
         $this->add(array(
             'name' => 'centro',
             'type' => 'Hidden',
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'class' =>'btn btn-lg btn-success',
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }