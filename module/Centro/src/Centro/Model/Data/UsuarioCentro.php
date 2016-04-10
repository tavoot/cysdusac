<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class UsuarioCentro
{
     public $centro_id;
     public $usuario_id;

     public function exchangeArray($data)
     {
         $this->centro_id  = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
         $this->usuario_id  = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
     }
 }