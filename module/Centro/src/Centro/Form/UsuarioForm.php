<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;
use Zend\Form\Element;

 class UsuarioForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('usuario');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         

          $this->add(array(
             'name' => 'tipo',
             'type' => 'Zend\Form\Element\Select',
             'label' => 'Seleccionar Tipo de Usuario',
         ));
         
         
         $this->add(array(
             'name' => 'usuario',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Usuario',
             ),
         ));
         $this->add(array(
             'name' => 'password',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Passowd',
             ),
         ));
        
         $this->add(array(
             'name' => 'email',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Email',
             ),
         ));
         
         $this->add(array(
             'name' => 'pais',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Pais',
             ),
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