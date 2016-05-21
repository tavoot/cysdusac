<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class Version
{
     public $id;
     public $fecha;
     public $version;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->fecha  = (!empty($data['fecha'])) ? $data['fecha'] : null;
         $this->version  = (!empty($data['version'])) ? $data['version'] : null;
     }
 }