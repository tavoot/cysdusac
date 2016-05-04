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
        
        //verifica si es un request para eliminar items
        $request = $this->getRequest();
        if ($request->isPost()){
            $canal_id = $this->params()->fromPost('canal');
            $canal = $this->getCanalTable()->get($canal_id);
            $this->getCanalTable()->delete($canal_id);

            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => $canal->centro_id,
            ));
        }
        
        
        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
                        'id'=>'x'
            ));
        }
        

        try { 
            //variable global
            $canales= $this->getCanalTable()->getByCentroCanal($centro_id, Catalogo::EXTERNO);
            $centro=$this->getCentroTable()->get($centro_id);
           
            return new ViewModel(array(
                'canales'=>$canales,  'centro'=>$centro
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

                // Redireccionar a la lista de canales
                return $this->redirect()->toRoute('canal', array(
                            'action' => 'listar',
                            'id' => $centro_id,
                ));
            }
        }

        
        $centro = $this->getCentroTable()->get($centro_id);
        return array(
            'form' => $form, 'centro' => $centro
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
            return $this->redirect()->toRoute('item', array(
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
}
