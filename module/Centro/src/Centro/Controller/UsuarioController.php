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
 * Description of UsuarioController
 *
 * @author nando
 */


class UsuarioController extends AbstractActionController
{
    protected $authAdapter;
    
    public function indexAction() {
        //return new ViewModel();
        return $this->redirect()->toRoute('usuario/default', array('action' => 'login'));
    }
    
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
                    
                            /*$no_almacenar = 'pin';



                            $usuario = $this->getAuthService()
                           ->getAdapter()
                           ->getResultRowObject(null, $no_almacenar);


                            $this->getAuthService()
                           ->getStorage()
                           ->write($usuario);

                            $usuario = $this->getAuthService()
                           ->getStorage()
                           ->read();*/
                    //var_dump($authService);
                     
                    
                    $this->flashMessenger()->addSuccessMessage('Bienvenido!!!!');
                    return $this->redirect()->toRoute('centro');
                } else {
                    $this->flashMessenger()->addErrorMessage('Usuario/Password incorrecto');
                    return $this->redirect()->toRoute('usuario/default', array('action' => 'login'));
                }
            }
        }
        
        //Cambio de layout para el controller
        $this->layout('layout/loginlayout');
        
        return array('form' => $loginForm);
    }
    
    public function logoutAction() {
        $this->getAuthService()->getStorage()->clear();
        return $this->redirect()->toRoute('usuario/default', array('action' => 'login'));
    }
    
    public function getAuthService() {
        if(!$this->authAdapter){
            $sm = $this->getServiceLocator();
            $this->authAdapter = $sm->get('Centro\Adapter\UsuarioAdapter');
        }
        
        return $this->authAdapter;
    }
    
    public function getConfig($configName){
        $config = $this->getServiceLocator()->get('Config');
        return $config[$configName];
    }
    
}