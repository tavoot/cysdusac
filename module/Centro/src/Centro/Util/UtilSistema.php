<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

use Zend\ServiceManager\ServiceLocatorInterface;
use Centro\Model\Data\Cambio;

Class UtilSistema {

    private $serviceManager;
    public $cambioTable;

    public function __construct(ServiceLocatorInterface $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    public function registrarCambio($valor, $centro) {
        $cambio = new Cambio();
        $cambio->exchangeArray(array('tipo' => $valor, 'centro_id' => $centro, 'version' => CatalogoValor::UNVERSIONED));
        $cambioTable = $this->serviceManager->get('Centro\Model\Logic\CambioTable');
        $cambioTable->save($cambio);
    }

}
