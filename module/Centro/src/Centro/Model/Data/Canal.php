<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;

class Canal
{
     public $id;
     public $tipo;
     public $titulo;
     public $descripcion;
     public $enlace;
     public $lenguaje;
     public $centro_id;
     
     

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
 }