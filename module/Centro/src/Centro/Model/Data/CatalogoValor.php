<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Centro\Model\Data;


class CatalogoValor
{
     public $id;
     public $valor;
     public $catalogo_tipo_id;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->valor  = (!empty($data['valor'])) ? $data['valor'] : null;
         $this->catalogo_tipo_id = (!empty($data['catalogo_tipo_id'])) ? $data['catalogo_tipo_id'] : null;
     }
 }