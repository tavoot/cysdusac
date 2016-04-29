<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use \Zend\Db\Sql\Select;
use Centro\Model\Data\Usuario;


class UsuarioTable{
    
    protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }
     
     public function fetchAll()
     {
        $select = new Select();
        $select->from('usuario');
        $select->join('catalogo_valor', 'usuario.tipo=catalogo_valor.id', array('valor'), 'INNER');
        $select->where('catalogo_tipo_id = 1');
         
        $resultSet= $this->tableGateway->selectWith($select);
        return $resultSet;
     }
     
     
     public function fetchAllByCentro($centro)
     {
        $select = new Select();
        $select->from('usuario');
        $select->join('usuario_centro', 'usuario.id = usuario_centro.usuario_id', array('usuario_id'), 'LEFT');
        $select->where(array('usuario_id is NULL'));
        /*$select->where('not exists (select 1 from usuario_centro ac where ac.usuario_id=u.id and ac.centro_id=)', $centro);*/
        
        var_dump($select->getSqlString());
         
        $resultSet= $this->tableGateway->selectWith($select);
        return $resultSet;
     }
     
     
     

     public function get($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Registro no encontrado con el id $id");
         }
         return $row;
     }
    
     
    public function save(Usuario $usuario)
    {
        
        $data = array(
            'tipo'=>$usuario->tipo, 
            'usuario'=>$usuario->usuario,  
            'password'=>$usuario->password, 
            'email'=>$usuario->email, 
            'pais'=>$usuario->pais);

         $id = (int) $usuario->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("El usuario con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}