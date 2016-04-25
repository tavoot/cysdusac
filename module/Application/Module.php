<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;




class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e){
            //$routeMatch = $e->getRouteMatch();
            
            $auth = $e->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
            $config = $e->getApplication()->getServiceManager()->get('Config');
            
            //$controller = $routeMatch->getParam('controller');
            //$action = $routeMatch->getParam('action');
            
            $controller = $e->getRouteMatch()->getParam('controller');
            $action = $e->getRouteMatch()->getParam('action');
            
            $requestedResource = $controller . '-' . $action;
            
            $whiteList = array(
                'Centro\Controller\Usuario-login',
                'Centro\Controller\Usuario-index',
            );
            
            echo in_array($requestedResource,$whiteList) . "<br>\n";
            
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    

}
