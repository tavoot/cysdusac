<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class UsuarioCentro
{
     public $id;
     public $centro_id;
     public $usuario_id;
     public $fecha;

     public function exchangeArray($data)
     {
         $this->id  = (!empty($data['id'])) ? $data['id'] : null;
         $this->centro_id  = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
         $this->usuario_id  = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
         $this->fecha  = (!empty($data['fecha'])) ? $data['fecha'] : null;
     }
 }