<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\Version;


class VersionTable{
    
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
     
     public function getLastValue(){
         $rowset = $this->tableGateway->select()->count();
         
         if (!$rowset) {
             return 0;
             //throw new \Exception("Problemas con el ultimo registro");
         }
         return $rowset;
     }

     public function get($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('version' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Registro no encontrado $id");
         }
         return $row;
     }

    public function save(Version $version)
    {
        
        $data = array(
            'fecha'=>$version->fecha,
            'version'=>$version->version);

         $id = (int) $version->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("La version con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}