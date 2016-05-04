<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\Contacto;
use \Zend\Db\Sql\Select;

class ContactoTable{
    
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
     
     public function getByCentroContacto($centro_id)
     {
        $centro_id  = (int) $centro_id;
        $select = new Select();
        $select->from('contacto');
        $select->join('centro', 'contacto.centro_id=centro.id', array('siglas'), 'INNER');
        $select->where(array('centro_id'=>$centro_id));
        $rowset = $this->tableGateway->selectWith($select);
         if (!$rowset) {
             throw new \Exception("Registro no encontrado $centro_id");
         }
         return $rowset;
     }

    public function save(Contacto $contacto)
    {
        $data = array(
            'nombre'=>$contacto->nombre,
            'email'=>$contacto->email, 
            'telefono'=>$contacto->telefono,
            'puesto'=>$contacto->puesto, 
            'centro_id'=>$contacto->centro_id);

         $id = (int) $contacto->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("El contaco con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}