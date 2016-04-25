<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Centro\Model\Data\Item;
use Centro\Form\ItemForm;

class ItemController extends AbstractActionController {

    protected $itemTable;

    public function indexAction(){
        return new ViewModel(array(
            'items' => $this->getItemTable()->fetchAll(),
        ));
    }
    

    public function getItemTable(){
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();
            $this->itemTable = $sm->get('Centro\Model\Logic\ItemTable');
        }
        return $this->itemTable;
    }

    public function addAction() {
        $form = new ItemForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $item = new Item();
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $canal->exchangeArray($form->getData());
                $this->getItemTable()->save($item);

                // Redireccionar a la lista de canales
                return $this->redirect()->toRoute('item');
            }
        }
        return array('form' => $form);
    }
    
    
     public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('item', array(
                 'action' => 'add'
             ));
         }

         // Get the Album with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $canal = $this->getItemTable()->get($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('item', array(
                 'action' => 'index'
             ));
         }

         $form  = new ItemForm();
         $form->bind($item);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($item->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getItemTable()->save($item);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('item');
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
             return $this->redirect()->toRoute('item');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getCanalTable()->delete($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('item');
         }

         return array(
             'id'    => $id,
             'canal' => $this->getItemTable()->get($id)
         );
     }

}
