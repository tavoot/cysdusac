<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;

 class VersionForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('version');

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
             'name' => 'sbtcancelar',
             'type' => 'Submit',
             'attributes' => array(
                 'class' =>'btn btn-lg btn-danger',
                 'value' => 'Cancelar',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }