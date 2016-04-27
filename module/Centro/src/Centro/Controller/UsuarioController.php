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
use Centro\Util\CatalogoTipo;
use Centro\Form\UsuarioForm;

class UsuarioController extends AbstractActionController {

    protected $usuarioTable;
    protected $catalogovalorTable;

    public function indexAction() {

        return new ViewModel(array(
            'usuarios' => $this->getUsuarioTable()->fetchAll(),
        ));
    }

    private function getUsuarioTable() {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Centro\Model\Logic\UsuarioTable');
        }
        return $this->usuarioTable;
    }

    public function addAction() {
        $form = new UsuarioForm();
        $this->getCatalogoUsuarios($form);
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            var_dump($request->getPost());
            var_dump("pruebas");
            var_dump($form->get('tipo')->getOptions());
            var_dump($form->get('tipo')->getOptions());
            var_dump($form->get('tipo')->getAttributes());
            var_dump($form->get('submit')->getName());

            $usuario = new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $usuario->exchangeArray($form->getData());
                $this->getUsuarioTable()->save($usuario);

                // Redireccionar a la lista de usuarios
                return $this->redirect()->toRoute('usuario');
            }
        }
        ;
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('usuario', array(
                        'action' => 'add'
            ));
        }

        try {
            $usuario = $this->getUsuarioTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('usuario', array(
                        'action' => 'index'
            ));
        }

        $form = new UsuarioForm();
        $this->getCatalogoUsuarios($form);


        $form->bind($usuario);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUsuarioTable()->save($usuario);

                // Redirect to list of albums
                return $this->redirect()->toRoute('usuario');
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
            return $this->redirect()->toRoute('usuario');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getUsuarioTable()->delete($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('usuario');
        }

        return array(
            'id' => $id,
            'usuario' => $this->getUsuarioTable()->get($id)
        );
    }

    private function getCatalogoValorTable() {
        if (!$this->catalogovalorTable) {
            $sm = $this->getServiceLocator();
            $this->catalogovalorTable = $sm->get('Centro\Model\Logic\CatalogoValorTable');
        }
        return $this->catalogovalorTable;
    }

    private function getCatalogoUsuarios($form) {
        if (!$this->catalogovalorTable) {
            $this->catalogovalorTable = $this->getCatalogoValorTable()->get(CatalogoTipo::USUARIO);
        }

        $keys = array();
        $values = array();

        if ($this->catalogovalorTable) {
            foreach ($this->catalogovalorTable as $catalogovalor) {
                array_push($keys, $catalogovalor->id);
                array_push($values, $catalogovalor->valor);
            }
        } else {
            return "error en la carga de catalogos para los usuarios";
        }

        $form->get('tipo')->setValueOptions(array(
            'catalogo_usuario' => array('options' => array_combine($keys, $values))
        ));
    }

}
