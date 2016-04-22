<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;

class Cambio
{
     public $id;
     public $tipo;
     public $version_id;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo  = (!empty($data['tipo'])) ? $data['tipo'] : null;
         $this->version_id  = (!empty($data['version_id'])) ? $data['version_id'] : null;
     }
 }