<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;

 class ItemForm extends Form
 {
    public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('item');

         $this->setAttribute("class", "col-md-6");
         
         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         
          $this->add(array(
             'name' => 'canal_id',
             'type' => 'Hidden',
         ));

         
         
         $this->add(array(
             'name' => 'titulo',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Titulo',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'enlace',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Enlace',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ),
         ));
         
        
        
         $this->add(array(
             'name' => 'descripcion',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Descripcion',
             ),
             'attributes' => array(
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