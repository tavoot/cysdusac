<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;

class Item
{
     public $id;
     public $titulo;
     public $enlace;
     public $descripcion;
     public $fecha_publicacion;
     public $canal_id;
     
     protected $inputFilter;
     
     public function exchangeArray($data)
     {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->titulo  = (!empty($data['titulo'])) ? $data['titulo'] : null;
        $this->enlace  = (!empty($data['enlace'])) ? $data['enlace'] : null;
        $this->descripcion  = (!empty($data['descripcion'])) ? $data['descripcion'] : null;
        $this->fecha_publicacion  = (!empty($data['fecha_publicacion'])) ? $data['fecha_publicacion'] : null;
        $this->canal_id  = (!empty($data['canal_id'])) ? $data['canal_id'] : null;
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