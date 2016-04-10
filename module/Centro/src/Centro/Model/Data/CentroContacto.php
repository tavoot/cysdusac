<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class CentroContacto
{
     public $centro_id;
     public $contacto_id;

     public function exchangeArray($data)
     {
         $this->centro_id  = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
         $this->contacto_id  = (!empty($data['contacto_id'])) ? $data['contacto_id'] : null;
     }
 }