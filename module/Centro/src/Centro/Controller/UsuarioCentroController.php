<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Model\Data\UsuarioCentro;
use Centro\Form\UsuarioCentroForm;

class UsuarioCentroController extends AbstractActionController {

    
    protected $usuariocentroTable;

    public function indexAction() {
        //var_dump($usuariocentro);
        //var_dump($this->getUsuarioCentroTable()->getCentrosPorUsuario(1));
        
        return new ViewModel(array(
            'usuarioscentros' => $this->getUsuarioCentroTable()->getCentrosPorUsuario(1)
        ));
        
    }


    public function getUsuarioCentroTable() {
        if (!$this->usuariocentroTable) {
            $sm = $this->getServiceLocator();
            $this->usuariocentroTable = $sm->get('Centro\Model\Logic\UsuarioCentroTable');
        }
        return $this->usuariocentroTable;
    }
    
    
   
    
    
    public function addAction() {
        $form = new UsarioCentroForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $centro = new Centro();
            $form->setInputFilter($centro->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $centro->exchangeArray($form->getData());
                $this->getCentroTable()->save($centro);

                // Redireccionar a la lista de centros
                return $this->redirect()->toRoute('centro');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $centro = $this->getCentroTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'index'
            ));
        }

        $form = new CentroForm();
        $form->bind($centro);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($centro->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCentroTable()->save($centro);

                // Redirect to list of albums
                return $this->redirect()->toRoute('centro');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCentroTable()->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('centro');
        }

        return array(
            'id' => $id,
            'centro' => $this->getCentroTable()->get($id)
        );
    }

}
