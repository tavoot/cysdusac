<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\Cambio;
use \Zend\Db\Sql\Select;

class CambioTable{
    
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

     public function get($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Registro no encontrado $id");
         }
         return $row;
     }
     
     public function getByVersion($version){
         $select = new Select();
         $select->from('cambio');
         $select->where(array('version' => $version));
         $rowset = $this->tableGateway->selectWith($select);
         if (!$rowset) {
             throw new \Exception("Registros no encontrados");
         }
         return $rowset;
     }
     

    public function save(Cambio $cambio)
    {
        $data = array(
            'tipo'=>$cambio->tipo,
            'version'=>$cambio->version,
            'centro_id'=>$cambio->centro_id);

         $id = (int) $cambio->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("El cambio con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}