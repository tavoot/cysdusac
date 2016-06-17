<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Model\Data\Canal;
use Centro\Form\CanalForm;
use Centro\Util\XmlGenerator;
use Centro\Util\FileManager;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\UtilSistema as Log;

class CanalController extends AbstractActionController {

    protected $canalTable;
    protected $centroTable;
    protected $centro;

    public function indexAction() {
        return new ViewModel(array(
            'canales' => $this->getCanalTable()->fetchAll(),
        ));
    }

    public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }

    public function getCanalTable() {
        if (!$this->canalTable) {
            $sm = $this->getServiceLocator();
            $this->canalTable = $sm->get('Centro\Model\Logic\CanalTable');
        }
        return $this->canalTable;
    }

    public function getParametersUrl($request) {
        $data = array();

        $data['protocolo'] = $request->getUri()->getScheme();
        $data['host'] = $request->getUri()->getHost();
        $data['basepath'] = $request->getBasePath();

        return $data;
    }

    public function listarAction() {

        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }


        try {
            //variable global
            $canales = $this->getCanalTable()->getByCentroCanal($centro_id, Catalogo::EXTERNO);
            $centro = $this->getCentroTable()->get($centro_id);

            return new ViewModel(array(
                'canales' => $canales, 'centro' => $centro,
            ));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => $canal_id,
            ));
        }
    }

    public function addAction() {
        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'add',
                        'id' => 'x'
            ));
        }

        $form = new CanalForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $submit = $request->getPost('submit', 'Cancelar');
            if($submit=='Aceptar'){
                $canal = new Canal();
                $form->setInputFilter($canal->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    //si es valido el formulario, obtengo el numero de canales
                    $canales = $this->getCanalTable()->getByCentroCanal($centro_id, Catalogo::EXTERNO);
                    $secuencia = (int) sizeof($canales) + 1;

                    $canal->exchangeArray($form->getData());
                    $canal->secuencia = $secuencia;
                    $canal->habilitado = 1;
                    $this->getCanalTable()->save($canal);

                    // actualizacion del config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                    //registrar cambio en el sistema cuando se agrega un canal al centro
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro_id);

                    // crear el archivo estadistico del canal
                    FileManager::addCanalFile($centro_id, $secuencia);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Canal agregado satisfactoriamente');
                }
            }
            // Redireccionar a la lista de canales
            return $this->redirect()->toRoute('canal', array(
                'action' => 'listar',
                'id' => $centro_id,    
            ));
        }



        $centro = $this->getCentroTable()->get($centro_id);
        return array(
            'form' => $form, 'centro' => $centro,
        );
    }


    public function editAction() {
        $canal_id = (int) $this->params()->fromRoute('id', 0);
        if (!$canal_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }

        try {
            $canal = $this->getCanalTable()->get($canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar'
            ));
        }
        $form = new CanalForm();
        $form->bind($canal);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit', 'Cancelar');
            if($submit=='Aceptar'){
                $form->setInputFilter($canal->getInputFilter());
                $form->setData($request->getPost());

                    if ($form->isValid()) {
                        if ($canal->tipo == Catalogo::EXTERNO)
                            $canal->habilitado = 1;
                        else {
                            $serverUrl = sprintf('%s://%s', $request->getUri()->getScheme(), $request->getUri()->getHost());
                            $urlCanalInterno = $serverUrl.$request->getBasePath().'/'.FileManager::PUBLIC_PATH_CENTROS.$centro->id.'/canal/canalrss.xml';
                            $canal->enlace = $urlCanalInterno;
                        }
                        $this->getCanalTable()->save($canal);

                        // actualizacion del config centros.xml
                        $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                        $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                        //registrar cambio en el sistema cuando se edita un canal al centro
                        $log = new Log($this->getServiceLocator());
                        $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro->id);

                        // mensaje de la transaccion
                        $this->flashMessenger()->addInfoMessage('Canal editado satisfactoriamente');
                    }
                }
                
                //redireccion de segun el tipo de canal
                if ($canal->tipo == Catalogo::EXTERNO) {
                    //redirigir a la lista de canales del centro
                    return $this->redirect()->toRoute('canal', array(
                                'action' => 'listar',
                                'id' => $centro->id,
                    ));
                } if ($canal->tipo == Catalogo::INTERNO) {
                    // actualizacion del archivo canalrss.xml
                    //$writer->writeXmlCentro($centro->id);

                    //redirigir a la lista de items con el id del canal
                    return $this->redirect()->toRoute('item', array(
                                'action' => 'listar',
                                'id' => $canal->id,
                    ));
                }
        }

        return array(
            'form' => $form, 'canal' => $canal, 'centro' => $centro
        );
    }

    public function deleteAction() {
        $canal_id = (int) $this->params()->fromRoute('id', 0);
        if (!$canal_id) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }

        try {
            $canal = $this->getCanalTable()->get($canal_id);
            $centro = $this->getCentroTable()->get($canal->centro_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('canal', array(
                        'action' => 'listar'
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $canal_actual = $this->getCanalTable()->get($id);

                //actualizar los indices de la secuencia para los demas valores
                $this->updateSecuencia($canal_actual);

                //elimina un canal de la base de datos
                $this->getCanalTable()->delete($id);

                // actualizacion del config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                //registrar cambio en el sistema cuando se edita un canal al centro
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::CAMBIO_DE_CANALES_DE_CENTRO, $centro->id);

                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Canal eliminado satisfactoriamente');
            }

            // redirigir a la lista de canalis del centro
            return $this->redirect()->toRoute('canal', array('action' => 'listar', 'id' => $centro->id));
        }

        return array(
            'id' => $canal_id,
            'canal' => $canal,
            'centro' => $centro
        );
    }

    private function updateSecuencia($canal_actual){
        $listacanales = $this->getCanalTable()->getByCentroCanal($canal_actual->centro_id, Catalogo::EXTERNO);
        foreach ($listacanales as $canal) {
            if ($canal_actual->secuencia < $canal->secuencia) {
                $canal->secuencia = (int) $canal->secuencia - 1;
                $this->getCanalTable()->save($canal);
            }
        }
    }

}
