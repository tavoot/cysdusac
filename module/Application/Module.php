<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;


class Module{

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e){
            //$routeMatch = $e->getRouteMatch();
            
            $auth = $e->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
            
            $controller = $e->getRouteMatch()->getParam('controller');
            $action = $e->getRouteMatch()->getParam('action');
            
            $requestedResource = $controller . '-' . $action;
            
            $whiteList = array(
                'Centro\Controller\Usuario-login',
                'Centro\Controller\Usuario-index',
            );
            
            if (!$auth->hasIdentity() && !in_array($requestedResource,$whiteList)){
                $url = $e->getRouter()->assemble(array(), array('name' => 'usuario/default'));
                $response = $e->getResponse();
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
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
