<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

use Zend\ServiceManager\ServiceLocatorInterface;
use Centro\Util\CatalogoValor;
/**
 * Description of XmlGenerator
 *
 * @author nando
 */
class XmlGenerator {
    // service manager para el acceso a las tablas
    private $serviceManager;
    
    // rutas donde se generaran los archivos
    private $pathConfig;
    private $pathCentros;


    // constantes para la generacion de los archivos
    // de configuracion
    const CONFIG_RELACIGER = 1;
    const CONFIG_CONTROL = 2;
    const CONFIG_CENTROS = 3;

    
    public function __construct(ServiceLocatorInterface $serviceManager) {
        $this->serviceManager = $serviceManager;
        $this->pathConfig = 'public/apprelaciger/data/config/';
        $this->pathCentros = 'public/apprelaciger/data/centros/';
    }

    
    /**
     * Genera el archivo xml de configuracion segun el parametro indicado
     * si crea el archivo retorna true, en caso contrario false
     * 
     * @param type $tipo_archivo
     * @return boolean
     */
    public function writeXmlConfig($tipo_archivo) {
        $writer = new \XMLWriter();
        
        switch ($tipo_archivo){
            case self::CONFIG_RELACIGER:
                $writer->openURI($this->pathConfig.'relaciger.xml');
                $writer->startDocument('1.0','UTF-8');
                $writer->setIndent(true);
                $writer->setIndentString('   ');
        
                // obtengo el centro con id 1 (siempre debe ser relaciger)
                $centroTable = $this->serviceManager->get('Centro\Model\Logic\CentroTable');
                $centro = $centroTable->get(1);
                
                // obtengo los contactos para dicho centro
                $contactoTable = $this->serviceManager->get('Centro\Model\Logic\ContactoTable');
                $listaContactos = $contactoTable->getByCentroContacto($centro->id);
                
                $writer->startElement('relaciger'); //<relaciger>
                    $writer->writeElement('nombre', $centro->nombre);
                    $writer->writeElement('siglas', $centro->siglas);
                    $writer->writeElement('web', $centro->sitio_web);
                    $writer->writeElement('imagen', $centro->url_imagen);
                
                    $writer->startElement('mision');
                        $writer->writeElement('texto', $centro->mision);
                        $writer->writeElement('stat', 'estadisticos');
                    $writer->endElement();
                    
                    $writer->startElement('vision');
                        $writer->writeElement('texto', $centro->vision);
                        $writer->writeElement('stat', 'estadisticos');
                    $writer->endElement();
                    
                    $writer->startElement('descripcion');
                        $writer->writeElement('texto', $centro->descripcion);
                        $writer->writeElement('stat', 'estadisticos');
                    $writer->endElement();
                    
                    $writer->startElement('lista');
                        $writer->writeElement('stat', 'estadisticos');
                        foreach ($listaContactos as $contacto){
                            $writer->startElement('contacto');
                                $writer->writeElement('nombre', $contacto->nombre);
                                $writer->writeElement('mail', $contacto->email);
                                $writer->writeElement('telefono', $contacto->telefono);
                            $writer->endElement();
                        }
                    $writer->endElement();
                
                $writer->endElement(); //</relaciger>
                $writer->endDocument();
                $writer->flush();
                break;
            case self::CONFIG_CONTROL:
                $writer->openURI($this->pathConfig.'control.xml');
                $writer->startDocument('1.0','UTF-8');
                $writer->setIndent(true);
                 $writer->setIndentString('   ');
                
                // obtengo la lista de cambios
                $cambioTable = $this->serviceManager->get('Centro\Model\Logic\cambioTable');
                $listaCambios = $cambioTable->fetchAll();
                
                $writer->startElement('log'); //<principal>
                foreach($listaCambios as $cambio){
                    $writer->startElement('cambio');
                    $writer->writeElement('tipo', $cambio->tipo);
                    $writer->writeElement('id', $cambio->centro_id);
                   $writer->endElement();
                }
                
                $writer->endElement(); //</principal>
                $writer->endDocument();
                $writer->flush();
                break;
                
            case self::CONFIG_CENTROS:
                $writer->openURI($this->pathConfig.'centros.xml');
                $writer->startDocument('1.0','UTF-8');
                $writer->setIndent(true);
                $writer->setIndentString('   ');
        
                // obtengo la lista de centros sin relaciger
                $centroTable = $this->serviceManager->get('Centro\Model\Logic\CentroTable');
                $listaCentros = $centroTable->fetchCentros();
                
                // obtengo la clase para acceder a los contactos
                $contactoTable = $this->serviceManager->get('Centro\Model\Logic\ContactoTable');
                // obtento la clase para acceder a los canales
                $canalTable = $this->serviceManager->get('Centro\Model\Logic\CanalTable');
                
                $writer->startElement('principal'); //<principal>
                    foreach ($listaCentros as $centro) {
                        $writer->startElement('centro');
                            $writer->writeElement('id', $centro->id);
                            $writer->writeElement('stat', 'estadistico');
                            $writer->writeElement('nombre', $centro->nombre);
                            $writer->writeElement('siglas', $centro->siglas);
                            $writer->writeElement('tipo', $centro->tipo);
                            $writer->writeElement('pais', $centro->pais);
                            $writer->writeElement('web', $centro->sitio_web);
                            $writer->writeElement('direccion', $centro->direccion);
                            $writer->writeElement('telefono', $centro->telefono);
                            
                            // lista de contactos por centro
                            $listaContactos = $contactoTable->getByCentroContacto($centro->id);
                            foreach ($listaContactos as $contacto) {
                                $writer->startElement('contacto');
                                    $writer->writeElement('nombre', $contacto->nombre);
                                    $writer->writeElement('puesto', $contacto->puesto);
                                    $writer->writeElement('mail', $contacto->email);
                                $writer->endElement();
                            }
                            
                            // lista de canales por centro
                            $listaCanales = $canalTable->fetchAllByCentro($centro->id);
                            foreach ($listaCanales as $canal) {
                                $writer->startElement('canal');
                                    $writer->writeElement('id', $canal->secuencia);
                                    $writer->writeElement('link', $canal->enlace);
                                    $writer->writeElement('stat', 'estadistico');
                                $writer->endElement();
                            }
                        
                        $writer->endElement();
                    }
                $writer->endElement(); //</principal>
                $writer->endDocument();
                $writer->flush();
                break;
            default :
                return false;
        }
        
        return true;
        
    }
    
    
    public function writeXmlCentro($centro_id) {
        $writer = new \XMLWriter();
        
        $writer->openURI($this->pathCentros."$centro_id/canal/canalrss.xml");
        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(true);
        $writer->setIndentString('   ');
        
        // obtenemos el canal interno
        $canalTable = $this->serviceManager->get('Centro\Model\Logic\CanalTable');
        $canalInterno = $canalTable->getByCentroCanal($centro_id, CatalogoValor::INTERNO)->current();
        
        // obtenemos la lista de items para el canal
        $itemTable = $this->serviceManager->get('Centro\Model\Logic\ItemTable');
        $listaItems = $itemTable->getByCanal($canalInterno->id);
        
        $writer->startElement('rss');
            $writer->startElement('channel');
                $writer->writeElement('title', $canalInterno->titulo);
                $writer->writeElement('description', $canalInterno->descripcion);
                $writer->writeElement('link', $canalInterno->enlace);
                $writer->writeElement('lenguage', $canalInterno->lenguaje);
                
                foreach ($listaItems as $item) {
                    $writer->startElement('item');
                        $writer->writeElement('title', $item->titulo);
                        $writer->writeElement('link', $item->enlace);
                        $writer->writeElement('description', $item->descripcion);
                        $writer->writeElement('pubDate', $item->fecha_publicacion);
                    $writer->endElement();
                }        
                
            $writer->endElement();
        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
        
    }
    
    
}
