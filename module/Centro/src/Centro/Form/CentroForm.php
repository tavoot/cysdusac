<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Form;

 class CentroForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('centro');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'nombre',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Nombre',
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
             'name' => 'siglas',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Siglas',
             ),
         ));
         $this->add(array(
             'name' => 'tipo',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Tipo',
             ),
         ));
         $this->add(array(
             'name' => 'sitio_web',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Sitio Web',
             ),
         ));
         
         $this->add(array(
             'name' => 'direccion',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Direccion',
             ),
         ));
         $this->add(array(
             'name' => 'telefono',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Telefono',
             ),
         ));
         
         $this->add(array(
             'name' => 'url_imagen',
             'type' => 'Text',
             'options' => array(
                 'label' => 'URL Imagen',
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