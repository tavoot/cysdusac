<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Centro\Model\Data\Version;
use Centro\Form\VersionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\XmlGenerator;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Request as HttpRequest;


use RuntimeException;

class VersionController extends AbstractActionController {

    public $cambioTable;
    public $versionTable;
    public $tipo;
    public $message;

    public function getVersionTable() {
        if (!$this->versionTable) {
            $sm = $this->getServiceLocator();
            $this->versionTable = $sm->get('Centro\Model\Logic\VersionTable');
        }
        return $this->versionTable;
    }
    
    public function getCambioTable() {
        if (!$this->cambioTable) {
            $sm = $this->getServiceLocator();
            $this->cambioTable = $sm->get('Centro\Model\Logic\CambioTable');
        }
        return $this->cambioTable;
    }
    
    public function addAction(){
        $form = new VersionForm();
        //$form->get('submit')->setValue('Agregar');
        
        
        //return array('form' => $form);
    }

    public function createAction() {
        $request = $this->getRequest();
        if ($request instanceof HttpRequest) {
            $this->procesar();
            return array('tipo' => $this->getTipo(), 'message'=>$this->getMessage());
            
        } elseif ($request instanceof ConsoleRequest) {
            $mode = $request->getParam('mode', 'all'); // defaults to 'all'
            switch ($mode) {
                case 'all':
                default:
                    $this->procesar();
                    echo $this->getMessage();
                    return;
            }
        } else {
            throw new RuntimeException('No se puede gestionar el tipo de solicitud  ' . get_class($request));
        }
    }

    private function procesar() {
        $initVersionado = false;
        $newversion = $this->getVersionTable()->getLastValue() + 1;

        //obtengo la lista de todos los cambios aun sin version el valor 0, me indica los que aun no lo estan
        $listaCambios = $this->getCambioTable()->getByVersion(Catalogo::UNVERSIONED);

        //verifico que haya cambios en el sistema
        if (isset($listaCambios)) {
            //actualizo el campo version de los cambios aun sin versionar
            foreach ($listaCambios as $cambio) {
                $initVersionado = true;
                $cambio->version = $newversion;
                $this->getCambioTable()->save($cambio);
            }

            if ($initVersionado) {
                // Agrego una nueva version al sistema
                $version = new Version();
                $version->exchangeArray(array('version' => $newversion));
                $this->getVersionTable()->save($version);

                // Escribo en el archivo control
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CONTROL);
                $this->setTipo(Catalogo::NEWVERSION);
                $this->setMessage("Se ha creado la version $newversion del fichero control".PHP_EOL);

            } else {
                $this->setTipo(Catalogo::OLDVERSION);
                $this->setMessage("No se han registrado cambios en el sistema".PHP_EOL);
            }
        }
    }
    
    
    function setTipo($tipo){
        $this->tipo = $tipo;
    }
    
    function getTipo(){
        return $this->tipo;
    }
    
    function setMessage($message){
        $this->message = $message;
    }
    
    function getMessage(){
        return $this->message;
    }

}
