<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Canal implements InputFilterAwareInterface {

     public $id;
     public $tipo;
     public $titulo;
     public $descripcion;
     public $enlace;
     public $lenguaje;
     public $centro_id;
     
     protected $inputFilter;
     

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo  = (!empty($data['tipo'])) ? $data['tipo'] : null;
         $this->titulo  = (!empty($data['titulo'])) ? $data['titulo'] : null;
         $this->descripcion  = (!empty($data['descripcion'])) ? $data['descripcion'] : null;
         $this->enlace  = (!empty($data['enlace'])) ? $data['enlace'] : null;
         $this->lenguaje  = (!empty($data['lenguaje'])) ? $data['lenguaje'] : null;
         $this->centro_id  = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
     }
     
     
     public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
 }