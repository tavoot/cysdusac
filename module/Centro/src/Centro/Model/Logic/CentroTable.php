<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\Centro;
use Zend\Db\Sql\Select;

class CentroTable{
    
    protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }
     
     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }
     
     public function fetchCentros()
     {
         $resultSet = $this->tableGateway->select(function(Select $select){
             $select->where->literal('id != 1');
         });
         
         return $resultSet;
     }

     public function get($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

    public function save(Centro $centro)
    {
     
         $data = array(
             'tipo' => $centro->tipo,
             'nombre'  => $centro->nombre,
             'siglas' => $centro->siglas,
             'pais'  => $centro->pais,
             'sitio_web' => $centro->sitio_web,
             'direccion'  => $centro->direccion,
             'telefono' => $centro->telefono,
             'url_imagen'  => $centro->url_imagen,
             'mision' => $centro->mision,
             'vision' => $centro->vision,
             'descripcion'=> $centro->descripcion
         );

         $id = (int) $centro->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
             $id = $this->tableGateway->getLastInsertValue();
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('El centro con el id no existe');
             }
         }
         
         return $id;
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}