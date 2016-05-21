<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Centro\Util\CatalogoValor;
use Application\Util\ApplicationAcl as Acl;


class Module{

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e){
            //$routeMatch = $e->getRouteMatch();
            
            $auth = $e->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
            $config = $e->getApplication()->getServiceManager()->get('Config');
            
            $acl = new Acl($config);
            
            $controller = $e->getRouteMatch()->getParam('controller');
            $action = $e->getRouteMatch()->getParam('action');
            
            $rol = Acl::ROL_DEFULT;
            
            if($auth->hasIdentity()) {
                $usuario = $auth->getStorage()->read();
                
                switch($usuario->tipo){
                    case CatalogoValor::ESTANDAR:
                        $rol = 'estandar';
                        break;
                    
                    case CatalogoValor::ADMINISTRATIVO:
                        $rol = 'administrador';
                        break;
                    default:
                        $rol = Acl::ROL_DEFULT;
                }
            }
            
            if(!$acl->hasResource($controller)) {
                throw new Exception('Recurso no definido: '.$controller);
            }
            
            if(!$acl->isAllowed($rol, $controller, $action)) {
                $url = $e->getRouter()->assemble(array('action' => 'inicio'), array('name' => 'centro'));
                $response = $e->getResponse();
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            }
            
            /*$requestedResource = $controller . '-' . $action;
            
            $whiteList = array(
                'Centro\Controller\Acceso-index',
                'Centro\Controller\Acceso-login',
            );
            
            if (!$auth->hasIdentity() && !in_array($requestedResource,$whiteList)){
                $url = $e->getRouter()->assemble(array(), array('name' => 'home'));
                $response = $e->getResponse();
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            }*/
            
            //Cambia de layout cuando es usuario estandar ya que el usuario de admin
            //es el default
            if($auth->hasIdentity()){
                $usuario = $auth->getStorage()->read();
                if($usuario->tipo == CatalogoValor::ESTANDAR){
                    $e->getViewModel()->setTemplate('layout/layout_standard');
                }
            }
            
        }
        , 100);

    }



    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
 
}
