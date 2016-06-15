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

         $this->setAttribute("class", "col-md-6");
         
         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         

          $this->add(array(
             'name' => 'tipo',
             'type' => 'Zend\Form\Element\Select',
             'options' => array(
                 'label' => 'Tipo de Usuario',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ), 
         ));
         
         
         $this->add(array(
             'name' => 'usuario',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Usuario',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese nombre de usuario',
                'class' => 'form-control',
             ),
         ));
         $this->add(array(
             'name' => 'password',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese password',
                'class' => 'form-control',
             ),
         ));
        
         $this->add(array(
             'name' => 'email',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Email',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese email',
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'pais',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Pais',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese pais',
                'class' => 'form-control',
             ),
         ));

         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'class' =>'btn btn-lg btn-success',
                 'value' => 'Aplicar',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }