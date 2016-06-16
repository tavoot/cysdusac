<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;

 class ContactoForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('contacto');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         
         $this->add(array(
             'name' => 'centro_id',
             'type' => 'Hidden',
         ));
         
         $this->add(array(
             'name' => 'nombre',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Nombre',
             ),
             'attributes' => array(
                 'placeholder' => 'Ingrese nombre',
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
             'name' => 'telefono',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Telefono',
             ),
             'attributes' => array(
                 'placeholder' => 'Ingrese telefono',
                 'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'puesto',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Puesto',
             ),
             'attributes' => array(
                 'placeholder' => 'Ingrese puesto',
                 'class' => 'form-control',
             ),
         ));

         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'class' =>'btn btn-lg btn-success',
                 'value' => 'Aceptar',
                 'id' => 'submitbutton',
             ),
         ));
         
          $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'class' =>'btn btn-lg btn-danger',
                 'value' => 'Cancelar',
             ),
         ));
     }
 }