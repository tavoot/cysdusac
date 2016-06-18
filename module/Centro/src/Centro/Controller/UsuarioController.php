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
use Centro\Form\UpdatePassForm;
use Centro\Form\InputFilter\UpdatePassInputFilter;
use Centro\Util\UtilUsuario;
use Centro\Util\Session;

class UsuarioController extends AbstractActionController {

    protected $usuarioTable;
    protected $catalogovalorTable;
    protected $usuariocentroTable;
    protected $centroTable;
    

    public function indexAction() {

        return new ViewModel(array(
            'usuarios' => $this->getUsuarioTable()->fetchAll(),
        ));
    }
    
    public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }
    
    private function getUsuarioCentroTable() {
        if (!$this->usuariocentroTable) {
            $sm = $this->getServiceLocator();
            $this->usuariocentroTable = $sm->get('Centro\Model\Logic\UsuarioCentroTable');
        }
        return $this->usuariocentroTable;
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
        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $usuario = new Usuario();
                $form->setInputFilter($usuario->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $config = $this->getConfig('encrypt');
                    $utilUser = new UtilUsuario($config);

                    $usuario->exchangeArray($form->getData());
                    // cifrado del password antes de almacenarlo
                    $usuario->password = $utilUser->cifrar($usuario->password);

                    $this->getUsuarioTable()->save($usuario);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Usuario agregado satisfactoriamente');
                    
                    // Redireccionar a la lista de usuarios
                    return $this->redirect()->toRoute('usuario');
                }
            }else{
                // Redireccionar a la lista de usuarios
                return $this->redirect()->toRoute('usuario');
            
            }
        }
        
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
            $pass = $usuario->password;
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('usuario', array(
                        'action' => 'index'
            ));
        }

        $config = $this->getConfig('encrypt');
        $utilUser = new UtilUsuario($config);

        $form = new UsuarioForm();
        $this->getCatalogoUsuarios($form);
        $form->bind($usuario);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $form->setInputFilter($usuario->getInputFilter());
                $form->setValidationGroup('id', 'tipo', 'usuario', 'email', 'pais');
                $form->setData($request->getPost());


                if ($form->isValid()) {
                    $usuario->password = $pass;
                    $this->getUsuarioTable()->save($usuario);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Usuario editado satisfactoriamente');
                    // Redirect to list of albums
                    return $this->redirect()->toRoute('usuario');
                }
            }else{
                // Redirect to list of albums
                return $this->redirect()->toRoute('usuario');
            }
            
        }


        $centros = array();
        $usuarioscentros = $this->getUsuarioCentroTable()->getByUsuario($id);
        foreach ($usuarioscentros as $usuariocentro) {
            $centro = $this->getCentroTable()->get($usuariocentro->centro_id);
            array_push($centros, $centro);
        }

        return array(
            'id' => $id,
            'form' => $form,
            'centros' => $centros,
        );
    }

    public function editpassAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('usuario', array('action' => 'add'));
        }

        try {
            $usuario = $this->getUsuarioTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('usuario', array('action' => 'index'));
        }
        
        $config = $this->getConfig('encrypt');
        $utilUser = new UtilUsuario($config);

        $form = new UsuarioForm();
        //$form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit', 'Cancelar');
            if ($submit == 'Aceptar') {
                $form->setInputFilter($usuario->getInputFilter());
                $form->setValidationGroup('password');
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $newPassword = $form->get('password')->getValue();
                    $usuario->password = $utilUser->cifrar($newPassword);

                    $this->getUsuarioTable()->save($usuario);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Password de usuario editado satisfactoriamente');
                    return $this->redirect()->toRoute('usuario');
                }
            }
            
            // Redirigir a usuarios
           return $this->redirect()->toRoute('usuario', array('action' => 'edit', 'id'=>$id));
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

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getUsuarioTable()->delete($id);
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Usuario eliminado satisfactoriamente');
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('usuario');
        }

        return array(
            'id' => $id,
            'usuario' => $this->getUsuarioTable()->get($id)
        );
    }

    public function perfilAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'inicio'
            ));
        }

        try {
            $usuario = $this->getUsuarioTable()->get($id);
            $tipoId = $usuario->tipo;
            $pass = $usuario->password;
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'inicio'
            ));
        }
        
        $config = $this->getConfig('encrypt');
        $utilUser = new UtilUsuario($config);

        $form = new UsuarioForm();
        $this->getCatalogoUsuarios($form);

        
        $form->bind($usuario);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if ($submit == 'Aceptar') {
                $form->setInputFilter($usuario->getInputFilter());
                $form->setValidationGroup('id', 'usuario', 'email', 'pais');
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $usuario->password = $pass;
                    $usuario->tipo = $tipoId;

                    $this->getUsuarioTable()->save($usuario);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Pefil de usuario actualizado satisfactoriamente');
                    // se realiza redireccion para que visualizar mensaje de la transaccion
                    return $this->redirect()->toRoute('usuario', array('action' => 'perfil', 'id' => $id));
                    
                } 
            } else {
                // redirigir al inicio en caso de cancelar
                return $this->redirect()->toRoute('centro', array('action' => 'inicio'));
            }
         
        }

        $tipoUsuario = Session::getUsuario($this->getServiceLocator())->tipo;
        
        return array(
            'id' => $id,
            'form' => $form,
            'tipoUsuario' => $tipoUsuario,
        );
    }
    
    public function cambiarpassAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro', array('action' => 'inicio'));
        }

        try {
            $usuario = $this->getUsuarioTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array('action' => 'inicio'));
        }
        
        $config = $this->getConfig('encrypt');
        $utilUser = new UtilUsuario($config);
        
        $passInputFilter = new UpdatePassInputFilter();
        $form = new UpdatePassForm($passInputFilter->getInputFilter());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if ($submit == 'Aceptar') {
                $form->setData($request->getPost());

                if ($form->isValid()) {

                    $passActual = $form->get('pass_actual')->getValue();
                    $passNuevo = $form->get('pass_nuevo')->getValue();

                    $passActual = $utilUser->cifrar($passActual);

                    if($usuario->password != $passActual){
                        $this->flashMessenger()->addErrorMessage('No pudo procesarse la operacion, verifique que haya ingresado correctamente sus datos');
                        return $this->redirect()->toRoute('usuario', array('action' => 'cambiarpass', 'id' => $id));
                    }

                    $usuario->password = $utilUser->cifrar($passNuevo);


                    $this->getUsuarioTable()->save($usuario);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Password actualizado satisfactoriamente');
                    // se redirije al perfil de usuario
                    return $this->redirect()->toRoute('usuario', array('action' => 'perfil', 'id' => $id));
                }
            } else {
                // solo para visualizar el mensaje de la transaccion
                return $this->redirect()->toRoute('usuario', array('action' => 'perfil', 'id' => $id));
            }
            
        }
        
        return array(
            'id' => $id,
            'form' => $form,
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
    
    public function getConfig($configName){
        $config = $this->getServiceLocator()->get('Config');
        return $config[$configName];
    }

}
