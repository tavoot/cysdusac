<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Centro\Model\Data;


class Centro
 {
     
     public $id;
     public $tipo;
     public $nombre;
     public $siglas;
     public $pais;
     public $sitio_web;
     public $direccion;
     public $telefono;
     public $url_imagen;
     

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo = (!empty($data['tipo'])) ? $data['tipo'] : null;
         $this->nombre  = (!empty($data['nombre'])) ? $data['nombre'] : null;
         $this->siglas  = (!empty($data['siglas'])) ? $data['siglas'] : null;
         $this->pais  = (!empty($data['pais'])) ? $data['pais'] : null;
         $this->sitio_web  = (!empty($data['sitio_web'])) ? $data['sitio_web'] : null;
         $this->direccion  = (!empty($data['direccion'])) ? $data['direccion'] : null;
         $this->telefono  = (!empty($data['telefono'])) ? $data['telefono'] : null;
         $this->url_imagen  = (!empty($data['url_imagen'])) ? $data['url_imagen'] : null;
         
     }
 }