<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class CatalogoTipo
{
     public $id;
     public $nombre;
     public $descripcion;
     

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre  = (!empty($data['nombre'])) ? $data['nombre'] : null;
         $this->descripcion  = (!empty($data['descripcion'])) ? $data['descripcion'] : null;
     }
 }