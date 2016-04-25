<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Acl\AppAcl as Acl;
use Application\Util\Util as AppUtil;


class Module{

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        //administracion de Acl
           $eventManager->attach('route', function($e) {
            $application = $e->getApplication();
            $routeMatch = $e->getRouteMatch();
            $sm = $application->getServiceManager();



            $auth = $sm->get('Zend\Authentication\AuthenticationService');
            /*$config = $sm->get('Config');
            $acl = new Acl($config);

            $role = Acl::DEFAULT_ROL;

            
            $controller = $routeMatch->getParam('controller');
            $action = $routeMatch->getParam('action');*/
            
            
            if ($auth->hasIdentity()) {
                $usr = $auth->getIdentity();
                //var_dump($usr);
                //var_dump($usr);
                // TODO we don't need that if the names of the roles are comming from the DB
                //$role = AppUtil::getRole(6);
                
                /*$log=$_SERVER['REMOTE_ADDR'] . '|' . $usr->codigo. '|'. $controller.'|'.$action;
                $sm->get('Zend\Log\Logger\Request')->crit($log);*/

            }

            

            /*if (!$acl->hasResource($controller)) {
                throw new \Exception('Resource ' . $controller . ' not defined');
            }


            if (!$acl->isAllowed($role, $controller, $action)) {
                $url = $e->getRouter()->assemble(array(), array('name' => 'home'));
                $response = $e->getResponse();

                $response->getHeaders()->addHeaderLine('Location', $url);
                // The HTTP response status code 302 Found is a common way of performing a redirection.
                // http://en.wikipedia.org/wiki/HTTP_302
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            }*/
        }, -100);
           
            

        //loggeo de sucesos
        //requests
        //errores
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error', function($e) use ($sm) {
            if ($e->getParam('exception')) {
                $sm->get('Zend\Log\Logger\Error')->crit($_SERVER['REMOTE_ADDR'] . '|' . $e->getParam('exception'));
            }
        }
        );
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
