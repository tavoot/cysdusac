<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Centro\Model\Data\Contacto;
use Centro\Form\ContactoForm;



class ContactoController extends AbstractActionController {

    protected $contactoTable;

    public function indexAction() {
        return new ViewModel(array(
            'contactos' => $this->getContactoTable()->fetchAll(),
        ));
    }

    public function getContactoTable() {
        if (!$this->contactoTable) {
            $sm = $this->getServiceLocator();
            $this->contactoTable = $sm->get('Centro\Model\Logic\ContactoTable');
        }
        return $this->contactoTable;
    }

    public function addAction() {
        $form = new ContactoForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $contacto= new Contacto();
            $form->setInputFilter($contacto->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $contacto->exchangeArray($form->getData());
                $this->getContactoTable()->save($contacto);

                // Redireccionar a la lista de usuarios
                return $this->redirect()->toRoute('contacto');
            }
        }
        return array('form' => $form);
    }
    
    
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('contacto', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $contacto = $this->getContactoTable()->get($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('contacto', array(
                 'action' => 'index'
             ));
         }

         $form  = new ContactoForm();
         $form->bind($contacto);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($contacto->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getUsuarioTable()->save($contacto);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('contacto');
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
             return $this->redirect()->toRoute('contacto');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getContactoTable()->delete($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('contacto');
         }

         return array(
             'id'    => $id,
             'usuario' => $this->getContactoTable()->get($id)
         );
     }

}
