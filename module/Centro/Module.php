<?php

namespace Centro;

use Centro\Model\Data\Usuario;
use Centro\Model\Logic\UsuarioTable;
use Centro\Adapter\UsuarioAdapter;
use Centro\Model\Data\Centro;
use Centro\Model\Logic\CentroTable;
use Centro\Model\Data\Canal;
use Centro\Model\Logic\CanalTable;
use Centro\Model\Data\Contacto;
use Centro\Model\Logic\ContactoTable;
use Centro\Model\Data\Item;
use Centro\Model\Logic\ItemTable;
use Centro\Model\Data\UsuarioCentro;
use Centro\Model\Logic\UsuarioCentroTable;

use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                         
                 /*usuarios*/
                'Centro\Model\Logic\UsuarioTable' => function($sm) {
                    $tableGateway = $sm->get('UsuarioTableGateway');
                    $table = new UsuarioTable($tableGateway);
                    return $table;
                },
                'UsuarioTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Usuario());
                    return new TableGateway('usuario', $dbAdapter, null, $resultSetPrototype);
                },
                
                        
                /*adapters*/
                'Centro\Adapter\UsuarioAdapter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $usuarioAdapter = new UsuarioAdapter($dbAdapter);
                    $authService = new AuthenticationService();
                    $authService->setAdapter($usuarioAdapter);
                    return $authService;
                },
                        
                        
                /*centros*/
                'Centro\Model\Logic\CentroTable' => function($sm) {
                    $tableGateway = $sm->get('CentroTableGateway');
                    $table = new CentroTable($tableGateway);
                    return $table;
                },
                'CentroTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Centro());
                    return new TableGateway('centro', $dbAdapter, null, $resultSetPrototype);
                },
               
                        
                /*canales*/
                'Centro\Model\Logic\CanalTable' => function($sm) {
                    $tableGateway = $sm->get('CanalTableGateway');
                    $table = new CanalTable($tableGateway);
                    return $table;
                },
                'CanalTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Canal());
                    return new TableGateway('canal', $dbAdapter, null, $resultSetPrototype);
                },
                        
                /*contactos*/
                'Centro\Model\Logic\ContactoTable' => function($sm) {
                    $tableGateway = $sm->get('ContactoTableGateway');
                    $table = new ContactoTable($tableGateway);
                    return $table;
                },
                'ContactoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Contacto());
                    return new TableGateway('contacto', $dbAdapter, null, $resultSetPrototype);
                },
                        
                 /*items*/
                'Centro\Model\Logic\ItemTable' => function($sm) {
                    $tableGateway = $sm->get('ItemTableGateway');
                    $table = new ItemTable($tableGateway);
                    return $table;
                },
                        
                'ItemTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Item());
                    return new TableGateway('item', $dbAdapter, null, $resultSetPrototype);
                },
                
                /*UsuarioCentros*/
                'Centro\Model\Logic\UsuarioCentroTable' => function($sm) {
                    $tableGateway = $sm->get('UsuarioCentroTableGateway');
                    $table = new UsuarioCentroTable($tableGateway);
                    return $table;
                },
                        
                'UsuarioCentroTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UsuarioCentro());
                    return new TableGateway('usuario_centro', $dbAdapter, null, $resultSetPrototype);
                },
                        
                        
                
            ),
        );
    }

}
