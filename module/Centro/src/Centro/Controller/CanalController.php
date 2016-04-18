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

class CanalController extends AbstractActionController {

    protected $canalTable;

    public function indexAction() {
        return new ViewModel(array(
            'canales' => $this->getCanalTable()->fetchAll(),
        ));
    }

    public function getCanalTable() {
        if (!$this->canalTable) {
            $sm = $this->getServiceLocator();
            $this->canalTable = $sm->get('Centro\Model\Logic\CanalTable');
        }
        return $this->canalTable;
    }

    public function addAction() {
        $form = new CanalForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $canal= new Canal();
            $form->setInputFilter($canal->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $canal->exchangeArray($form->getData());
                $this->getCanalTable()->save($canal);

                // Redireccionar a la lista de canales
                return $this->redirect()->toRoute('canal');
            }
        }
        return array('form' => $form);
    }
    
    
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('canal', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $canal = $this->getCanalTable()->get($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('canal', array(
                 'action' => 'index'
             ));
         }

         $form  = new CanalForm();
         $form->bind($canal);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($canal->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getCanalTable()->save($canal);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('canal');
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
             return $this->redirect()->toRoute('canal');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getCanalTable()->delete($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('canal');
         }

         return array(
             'id'    => $id,
             'canal' => $this->getCanalTable()->get($id)
         );
     }

}
