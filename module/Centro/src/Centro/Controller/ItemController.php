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
use Centro\Util\XmlGenerator;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\UtilSistema as Log;

class ItemController extends AbstractActionController {

    protected $itemTable;
    protected $canalTable;
    protected $centroTable;
    protected $canal;
    protected $centro;

    public function indexAction() {
        return new ViewModel(array(
            'items' => $this->getItemTable()->fetchAll(),
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

    public function getItemTable() {
        if (!$this->itemTable) {
            $sm = $this->getServiceLocator();
            $this->itemTable = $sm->get('Centro\Model\Logic\ItemTable');
        }
        return $this->itemTable;
    }

    public function listarAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
            ));
        }

        try {
            //variable global
            $items = $this->getItemTable()->getByCanal($id);
            $this->canal = $this->getCanalTable()->get($id);
            if ($this->canal) {
                $this->centro = $this->getCentroTable()->get($this->canal->centro_id);
            }

            return new ViewModel(array(
                'items' => $items, 'canal' => $this->canal, 'centro' => $this->centro
            ));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
                        'id' => $id,
            ));
        }
    }

    public function addAction() {
        $canal_id = (int) $this->params()->fromRoute('id', 0);
        if (!$canal_id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'add'
            ));
        }

        $form = new ItemForm();
        $form->get('submit')->setValue('Agregar');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $item = new Item();
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $item->exchangeArray($form->getData());
                $this->getItemTable()->save($item);




                //registrar cambio en el sistema cuando se agrega un item de un canal al centro
                $canal = $this->getCanalTable()->get($canal_id);
                $centro = $this->getCentroTable()->get($canal->centro_id);
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro->id);


                // Redireccionar a la lista de canales
                return $this->redirect()->toRoute('item', array(
                            'action' => 'listar',
                            'id' => $canal_id,
                ));
            }
        }

        $this->canal = $this->getCanalTable()->get($canal_id);
        if ($this->canal) {
            $this->centro = $this->getCentroTable()->get($this->canal->centro_id);
        }
        return array(
            'form' => $form, 'canal' => $this->canal, 'centro' => $this->centro
        );
    }

    public function editAction() {
        $item_id = (int) $this->params()->fromRoute('id', 0);
        if (!$item_id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
            ));
        }

        try {
            $item = $this->getItemTable()->get($item_id);
            $canal = $this->getCanalTable()->get($item->canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar'
            ));
        }

        $form = new ItemForm();
        $form->bind($item);
        $form->get('submit')->setAttribute('value', 'Aplicar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($item->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getItemTable()->save($item);


                //registrar cambio en el sistema cuando se eidta un item de un canal al centro
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro->id);

                return $this->redirect()->toRoute('item', array(
                            'action' => 'listar',
                            'id' => $item->canal_id,
                ));
            }
        }

        return array(
            'form' => $form, 'canal' => $canal, 'centro' => $centro, 'item' => $item
        );
    }

    public function deleteAction() {
        $item_id = (int) $this->params()->fromRoute('id', 0);
        if (!$item_id) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
                        'id'=>'x'
            ));
        }

        try {
            $item = $this->getItemTable()->get($item_id);
            $canal = $this->getCanalTable()->get($item->canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('item', array(
                        'action' => 'listar',
                        'id'=>'x'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');


                //elimina un canal de la base de datos
                $this->getItemTable()->delete($id);


                //registrar cambio en el sistema cuando se elimina un canal al centro
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro->id);

                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Noticia eliminada satisfactoriamente');
            }

            // redirigir a la lista de canalis del centro
            return $this->redirect()->toRoute('item', array('action' => 'listar', 'id' => $canal->id));
        }

        return array(
            'id' => $item_id,
            'item'=>$item,
            'canal' => $canal,
            'centro' => $centro
        );
    }

}
