<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class Contacto
{
     public $id;
     public $nombre;
     public $email;
     public $telefono;
     

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre  = (!empty($data['nombre'])) ? $data['nombre'] : null;
         $this->email  = (!empty($data['email'])) ? $data['email'] : null;
         $this->telefono  = (!empty($data['telfono'])) ? $data['telefono'] : null;
     }
 }