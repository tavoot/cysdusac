<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Centro\Model\Data\Usuario;
use Centro\Form\UsuarioForm;



class UserController extends AbstractActionController {

    protected $usuarioTable;

    public function indexAction() {
        return new ViewModel(array(
            'usuarios' => $this->getUsuarioTable()->fetchAll(),
        ));
    }

    public function getUsuarioTable() {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Centro\Model\Logic\UsuarioTable');
        }
        return $this->usuarioTable;
    }

    public function addAction() {
        $form = new UsuarioForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $usuario= new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $usuario->exchangeArray($form->getData());
                $this->getUsuarioTable()->save($usuario);

                // Redireccionar a la lista de usuarios
                return $this->redirect()->toRoute('user');
            }
        }
        return array('form' => $form);
    }
    
    
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('user', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $usuario = $this->getUsuarioTable()->get($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('user', array(
                 'action' => 'index'
             ));
         }

         $form  = new UsuarioForm();
         $form->bind($usuario);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($usuario->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getUsuarioTable()->save($usuario);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('user');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }
     
     
     public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('user');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getUsuarioTable()->delete($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('user');
         }

         return array(
             'id'    => $id,
             'usuario' => $this->getUsuarioTable()->get($id)
         );
     }

}
