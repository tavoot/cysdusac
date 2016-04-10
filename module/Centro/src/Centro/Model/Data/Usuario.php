<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


class Usuario
{
     public $id;
     public $tipo;
     public $usuario;
     public $password;
     public $email;
     public $pais;
     
     
     public function exchangeArray($data)
     {
         $this->id  = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo  = (!empty($data['tipo'])) ? $data['tipo'] : null;
         $this->usuario = (!empty($data['usuario'])) ? $data['usuario'] : null;
         $this->password  = (!empty($data['password'])) ? $data['password'] : null;
         $this->email = (!empty($data['email'])) ? $data['email'] : null;
         $this->pais  = (!empty($data['pais'])) ? $data['pais'] : null;
     }
 }