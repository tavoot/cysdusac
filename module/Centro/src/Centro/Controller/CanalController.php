<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Model\Data\Canal;
use Centro\Form\CanalForm;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\XmlGenerator;

class CanalController extends AbstractActionController {

    protected $canalTable;
    protected $centroTable;
    protected $centro;

    public function indexAction() {
        return new ViewModel(array(
            'canales' => $this->getCanalTable()->fetchAll(),
        ));
    }

     public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }
    
   
    public function getCanalTable() {
        if (!$this->canalTable) {
            $sm = $this->getServiceLocator();
            $this->canalTable = $sm->get('Centro\Model\Logic\CanalTable');
        }
        return $this->canalTable;
    }
    
    
     public function listarAction() {
        //verifica si es un request para eliminar canales
        $request = $this->getRequest();
        if ($request->isPost()){
            $canal_id = $this->params()->fromPost('canal');
            $canal_actual = $this->getCanalTable()->get($canal_id);
            
            
            //actualizar los indices de la secuencia para los demas valores
            $canales = $this->getCanalTable()->getByCentroCanal($canal_actual->centro_id, Catalogo::EXTERNO);
            foreach($canales as $canal){
                if($canal_actual->secuencia < $canal->secuencia){
                    $canal->secuencia = (int) $canal->secuencia - 1;
                    $this->getCanalTable()->save($canal);
                }
            }

            //elimina un canal de la base de datos
            $this->getCanalTable()->delete($canal_id);
            

            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => $canal_actual->centro_id,
            ));
        }
        
        
        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id'=>'x'
            ));
        }
        

        try { 
            //variable global
            $canales= $this->getCanalTable()->getByCentroCanal($centro_id, Catalogo::EXTERNO);
            $centro=$this->getCentroTable()->get($centro_id);
            
            return new ViewModel(array(
                'canales'=>$canales,  'centro'=>$centro,
            ));
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id'=>$canal_id,
            ));
        }
    }
    

 
    public function addAction() {
        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'add',
                        'id' => 'x'
            ));
        }

        $form = new CanalForm();
        $form->get('submit')->setValue('Agregar');
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $canal= new Canal();
            $form->setInputFilter($canal->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()){
                $canal->exchangeArray($form->getData());
                $this->getCanalTable()->save($canal);
                
                // actualizacion del config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // aqui llamada al filemanager
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Canal agregado satisfactoriamente');
                // Redireccionar a la lista de canales
                return $this->redirect()->toRoute('canal', array(
                            'action' => 'listar',
                            'id' => $centro_id,
                ));
            }
        }

        $canales= $this->getCanalTable()->getByCentroCanal($centro_id, Catalogo::EXTERNO);
        $secuencia = (int) sizeof($canales) +  1;
        
        $centro = $this->getCentroTable()->get($centro_id);
        return array(
            'form' => $form, 'centro' => $centro, 'secuencia'=>$secuencia
        );
    }
    
    
    public function editAction() {
        $canal_id = (int) $this->params()->fromRoute('id', 0);
        if (!$canal_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }
        
        try {
            $canal = $this->getCanalTable()->get($canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar'
            ));
        }

        $form = new CanalForm();
        $form->bind($canal);
        $form->get('submit')->setAttribute('value', 'Aplicar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($canal->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCanalTable()->save($canal);

                // actualizacion del config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Canal editado satisfactoriamente');
                // redirigir a la lista de canales del centro
                return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id'=>$canal->centro_id,
                ));
            }
        }

        return array(
            'form' => $form, 'canal' => $canal, 'centro' => $centro 
        );
    }
    
    public function deleteAction(){
        $canal_id = (int) $this->params()->fromRoute('id', 0);
        if (!$canal_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }
        
        try {
            $canal = $this->getCanalTable()->get($canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            
            if($del == 'Si'){
                $id = (int) $request->getPost('id');
                $this->getCanalTable()->delete($id);
                
                // actualizacion del config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                // aqui llamada al filemanager
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Canal eliminado satisfactoriamente');
            }
            
            // redirigir a la lista de canalis del centro
            return $this->redirect()->toRoute('canal', array('action' => 'listar', 'id' => $centro->id));
        }

        return array(
            'id' => $canal_id, 
            'canal' => $canal, 
            'centro' => $centro 
        );
    }
    
}
