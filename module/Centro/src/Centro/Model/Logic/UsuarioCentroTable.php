<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\UsuarioCentro;
use \Zend\Db\Sql\Select;

class UsuarioCentroTable{
    
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
         /*$id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));*/
         
        $id  = (int) $id;
        $select = new Select();
        $select->from('usuario_centro');
        $select->join('usuario', 'usuario.id=usuario_centro.usuario_id', array('usuario'), 'INNER');
        $select->join('centro', 'centro.id=usuario_centro.centro_id', array('siglas'), 'INNER');
        $select->where(array('usuario_centro.id' => $id));
         
        $rowset = $this->tableGateway->selectWith($select); 
         
         
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Registro no encontrado $id");
         }
         return $row;
     }
     
     public function getByCentro($id)
     {
        $id  = (int) $id;
        $select = new Select();
        $select->from('usuario_centro');
        $select->join('usuario', 'usuario.id=usuario_centro.usuario_id', array('usuario'), 'INNER');
        $select->join('centro', 'centro.id=usuario_centro.centro_id', array('siglas'), 'INNER');
        $select->where(array('centro_id' => $id));
         
        $rowset = $this->tableGateway->selectWith($select); 
         if (!$rowset) {
             throw new \Exception("Registro no encontrado $id");
         }
         return $rowset;
     }
     
     
     public function getByUsuario($id)
     {
         $id  = (int)$id;
         $rowset = $this->tableGateway->select(array('usuario_id' =>$id, 'centro_id > 1'));
         //$row = $rowset->current();
         if (!$rowset) {
             throw new \Exception("Registro no encontrado $id");
         }
         return $rowset;
     }

     
    public function save(UsuarioCentro $usuarioCentro)
    {
        
        $data = array(
            'usuario_id'=>$usuarioCentro->usuario_id,
            'centro_id'=>$usuarioCentro->centro_id,
            'fecha'=>$usuarioCentro->fecha);

         $id = (int) $usuarioCentro->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("El detalle del centro con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}