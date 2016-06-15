<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form;

use Zend\Form\Element;
use Zend\Form\Form;

 class CentroForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('centro');

         $this->setAttribute('class', 'col-md-6');
         
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
             'attributes' => array(
                'placeholder' => 'Ingrese nombre',
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'tipo',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Tipo',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese tipo de centro',
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
             'name' => 'siglas',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Siglas',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese siglas',
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'sitio_web',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Sitio Web',
             ),
             'attributes' => array(
                'placeholder' => 'http://www.ejemplo.org',
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'direccion',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Direccion',
             ),
             'attributes' => array(
                'placeholder' => 'Ingrese direccion',
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
             'name' => 'url_imagen',
             'type' => 'Text',
             'options' => array(
                 'label' => 'URL Imagen',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ),
         ));
         
         
         /*campos para relaciger*/
         $this->add(array(
             'name' => 'mision',
             'type' => 'Zend\Form\Element\Textarea',
             'options' => array(
                 'label' => 'Mision',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'vision',
             'type' => 'Zend\Form\Element\Textarea',
             'options' => array(
                 'label' => 'Vision',
             ),
             'attributes' => array(
                'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
             'name' => 'descripcion',
             'type' => 'Zend\Form\Element\Textarea',
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