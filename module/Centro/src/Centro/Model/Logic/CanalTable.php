<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Logic;

use Zend\Db\TableGateway\TableGateway;
use Centro\Model\Data\Canal;
use \Zend\Db\Sql\Select;
use Centro\Util\CatalogoTipo as Catalogo;

class CanalTable{
    
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
     
     
   
     public function getByCentroCanal($centro_id, $tipo_canal)
     {
        $centro_id  = (int) $centro_id;
        $select = new Select();
        $select->from('canal');
        $select->join('catalogo_valor', 'canal.tipo=catalogo_valor.id', array('valor'), 'INNER');
        $select->where(array('catalogo_tipo_id'=> Catalogo::CANAL, 'centro_id'=>$centro_id, 'tipo'=>$tipo_canal));
        $rowset = $this->tableGateway->selectWith($select);
         if (!$rowset) {
             throw new \Exception("Registro no encontrado $centro_id");
         }
         return $rowset;
     }

    public function save(Canal $canal)
    {
        $data = array(
            'tipo'=>$canal->tipo,
            'titulo'=>$canal->titulo,
            'enlace'=>$canal->enlace,
            'lenguaje'=>$canal->lenguaje,
            'descripcion'=>$canal->descripcion,
            'secuencia'=>$canal->secuencia,
            'centro_id'=>$canal->centro_id);

         $id = (int) $canal->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->get($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception("El canal con el id $id no existe");
             }
         }
     }

     public function delete($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

}