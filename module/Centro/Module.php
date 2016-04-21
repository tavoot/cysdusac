<?php

namespace Centro;

use Centro\Model\Data\Centro;
use Centro\Model\Logic\CentroTable;
use Centro\Adapter\UsuarioAdapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\AuthenticationService;

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
                'Centro\Adapter\UsuarioAdapter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $usuarioAdapter = new UsuarioAdapter($dbAdapter);
                    $authService = new AuthenticationService();
                    $authService->setAdapter($usuarioAdapter);
                    return $authService;
                },
            ),
        );
    }

}
