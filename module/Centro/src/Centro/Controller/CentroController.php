<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Model\Data\Centro;
use Centro\Model\Data\Usuario;
use Centro\Form\CentroForm;
use Centro\Util\Session;
use Centro\Util\XmlGenerator;
use Centro\Util\FileManager;


class CentroController extends AbstractActionController {

    const RELACIGER = 1;
    
    protected $centroTable;
    protected $usuario;
    

    public function indexAction() {
        
        //$usuario->getUsuarioCentroTable()->getCentrosPorUsuario($usuario->id);
        
        return new ViewModel(array(
            'centros' => $this->getCentroTable()->fetchCentros(),
        ));
        
        
    }

    public function findAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'find'
            ));
        }

        try {
            $centro = $this->getCentroTable()->get($id);
            return new ViewModel(array(
                'centro' => $centro,
            ));
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'index'
            ));
        }
    }

    public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }

    public function addAction() {
        $form = new CentroForm();
        $form->get('submit')->setValue('Agregar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $centro = new Centro();
            $form->setInputFilter($centro->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $centro->exchangeArray($form->getData());
                $id = $this->getCentroTable()->save($centro);
                
                // creamos el directorio del nuevo centro
                if(!isset($id)) {
                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('AVISO: No se pudo agregar el centro');
                    // Redireccionar a la lista de centros
                    return $this->redirect()->toRoute('centro');
                }
                FileManager::addCentroFolder($id);
                // actualizamos el config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Centro agregado satisfactoriamente');
                // Redireccionar a la lista de centros
                return $this->redirect()->toRoute('centro');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $centro = $this->getCentroTable()->get($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'index'
            ));
        }

        $form = new CentroForm();
        $form->bind($centro);
        $form->get('submit')->setAttribute('value', 'Aplicar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($centro->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCentroTable()->save($centro);

                // actualizamos el config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Centro editado satisfactoriamente');
                // Redirect to list of albums
                return $this->redirect()->toRoute('centro');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('centro');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getCentroTable()->delete($id);
                
                // eliminamos la carpeta del centro
                $fileManager = new FileManager();
                $fileManager->removeCentroFolder($id);
                // actualizamos el config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Centro eliminado satisfactoriamente');
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('centro');
        }

        return array(
            'id' => $id,
            'centro' => $this->getCentroTable()->get($id)
        );
    }
    
    public function relacigerAction(){
        try {
            // instancia del centro que sera manipulada por el form
            $centro = $this->getCentroTable()->get(self::RELACIGER);
            // instancia que mantendra los datos que no se modifican
            $relaciger = $this->getCentroTable()->get(self::RELACIGER);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'index'
            ));
        }
        
        $form = new CentroForm();
        $form->bind($centro);
        $form->get('submit')->setValue('Agregar');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($centro->getInputFilter());
            $form->setValidationGroup('id','mision', 'vision', 'descripcion');
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                // actualizacion de los campos enviados
                $relaciger->mision = $centro->mision;
                $relaciger->vision = $centro->vision;
                $relaciger->descripcion = $centro->descripcion;
                
                // guardo los cambios
                $this->getCentroTable()->save($relaciger);
                // actualizamos el config relaciger.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_RELACIGER);
                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('RELACIGER - Informacion general agregada');
                // Redireccionar a la lista de centros
                return $this->redirect()->toRoute('centro');
            }
            //var_dump($form->getMessages());
            //var_dump($form->getInputFilter()->getRawValues());
        }
        
        return array('form' => $form);
    }

}
