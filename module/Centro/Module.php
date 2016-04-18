<?php

namespace Centro;

use Centro\Model\Data\Centro;
use Centro\Model\Data\Usuario;
use Centro\Model\Data\Canal;
use Centro\Model\Logic\CentroTable;
use Centro\Model\Logic\CanalTable;
use Centro\Model\Logic\UsuarioTable;
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
            ),
        );
    }

}
