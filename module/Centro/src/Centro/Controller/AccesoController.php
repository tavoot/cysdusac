<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Form\LoginForm;
use Centro\Form\InputFilter\LoginInputFilter;
use Centro\Util\UtilUsuario;
use Centro\Adapter\UsuarioAdapter;

/**
 * Description of UserAccountController
 *
 * @author nando
 */
class AccesoController extends AbstractActionController {
    //put your code here
    
    protected $authAdapter;
    
    public function indexAction(){
        return $this->redirect()->toRoute('acceso/default', array('action' => 'login'));
    }
    
    /**
     * action que maneja el login de usuarios al sistema
     * @return LoginForm
     */
    public function loginAction() {
        $loginInputFilter = new LoginInputFilter();
        $loginForm = new LoginForm($loginInputFilter->getInputFilter());
        
        $request = $this->getRequest();
        $authService = $this->getAuthService();
        
        if($authService->hasIdentity()) {
            $this->redirect()->toRoute('centro');
        }
        
        if($request->isPost()){
            $loginForm->setData($request->getPost());
            
            if($loginForm->isValid()){
                $dataUser = $request->getPost();
                
                $config = $this->getConfig('encrypt');
                $usrUtil = new UtilUsuario($config);
                
                //Password ingresado por el usuario
                $password = $dataUser['password'];
                $password_cifrado = $usrUtil->cifrar($password);
                //Nombre de usuario ingresado
                $usuario = $dataUser['usuario'];
                
                
                $authService->getAdapter()->setDatos($usuario, $password_cifrado);
                $authService->authenticate();
                
                if($authService->hasIdentity()){

                    //Datos a omitir 
                    $omitirColumna = array('password','pais');
                    
                    //Obtengo los datos que se almacenaran en la sesion
                    $usuario = $authService->getAdapter()->getResultRowObject(null, $omitirColumna);
                    
                    //Almaceno los datos de la sesion
                    $authService->getStorage()->write($usuario);
                    
                    $this->flashMessenger()->addSuccessMessage('Bienvenido al sistema');

                    return $this->redirect()->toRoute('centro', array('action' => 'inicio'));
                } else {
                    $this->flashMessenger()->addErrorMessage('Usuario/Password ingresado es incorrecto');
                    return $this->redirect()->toRoute('acceso/default', array('action' => 'login'));
                }
            }
        }
        
        //Cambio de layout para el controller
        $this->layout('layout/loginlayout');
        
        return array('form' => $loginForm);
    }
    
    /**
     * action que maneja el logout de usuarios al sistema
     * @return Route
     */
    public function logoutAction() {
        $this->getAuthService()->getStorage()->clear();
        return $this->redirect()->toRoute('acceso/default', array('action' => 'login'));
    }
    
    /**
     * retorna una instancia de UsuarioAdapter para la autenticacion
     * de usuarios al sistema
     * @return UsuarioAdapter
     */
    public function getAuthService() {
        if(!$this->authAdapter){
            $sm = $this->getServiceLocator();
            $this->authAdapter = $sm->get('Centro\Adapter\UsuarioAdapter');
        }
        
        return $this->authAdapter;
    }
    
    /**
     * 
     * @param String $configName
     * @return Config
     */
    public function getConfig($configName){
        $config = $this->getServiceLocator()->get('Config');
        return $config[$configName];
    }
    
    
}
