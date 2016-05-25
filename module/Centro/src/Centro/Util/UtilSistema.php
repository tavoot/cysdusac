<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

use Zend\ServiceManager\ServiceLocatorInterface;
use Centro\Model\Data\Cambio;
use Centro\Model\Data\Version;

Class UtilSistema {

    private $serviceManager;
    public $cambioTable;

    public function __construct(ServiceLocatorInterface $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    public function registrarCambio($valor, $centro) {
        $cambio = new Cambio();
        $cambio->exchangeArray(array('tipo' => $valor, 'centro_id' => $centro, 'version'=>  CatalogoValor::UNVERSIONED));
        $cambioTable = $this->serviceManager->get('Centro\Model\Logic\CambioTable');
        $cambioTable->save($cambio);
    }
    

    public function registrarVersion() {
        $initVersionado=false;
        $versionTable = $this->serviceManager->get('Centro\Model\Logic\VersionTable');
        $newversion = $versionTable->getLastValue() + 1;

        //obtengo la lista de todos los cambios aun sin version el valor 0, me indica los que aun no lo estan
        $cambioTable = $this->serviceManager->get('Centro\Model\Logic\CambioTable');
        $listaCambios = $cambioTable->getByVersion(CatalogoValor::UNVERSIONED);

        
        //verifico que haya cambios en el sistema
        if (isset($listaCambios)) {
            //actualizo el campo version de los cambios aun sin versionar
            foreach ($listaCambios as $cambio) {
                $initVersionado = true;
                $cambio->version = $newversion;
                $cambioTable = $this->serviceManager->get('Centro\Model\Logic\CambioTable');
                $cambioTable->save($cambio);
            }
           
            if($initVersionado){
                // Agrego una nueva version al sistema
                $version = new Version();
                $version->exchangeArray(array('version' => $newversion));
                $newversionTable = $this->serviceManager->get('Centro\Model\Logic\VersionTable');
                $newversionTable->save($version);
                
                // Escribo en el archivo control
                $writer = new XmlGenerator($this->serviceManager);
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CONTROL);
            
            }
        }
    }

}
