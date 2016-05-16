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
use Centro\Model\Data\UsuarioCentro;
use Centro\Form\CentroForm;
use Centro\Form\UploadForm;
use Centro\Util\Session;
use Centro\Util\XmlGenerator;
use Centro\Util\FileManager;
use Zend\File\Transfer\Adapter\Http;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\ImageSize;


class CentroController extends AbstractActionController {

    const RELACIGER = 1;
    
    protected $contactoTable;
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
    
    public function getUsuarioCentroTable() {
        if (!$this->usuariocentroTable) {
            $sm = $this->getServiceLocator();
            $this->usuariocentroTable = $sm->get('Centro\Model\Logic\UsuarioCentroTable');
        }
        return $this->usuariocentroTable;
    }
    
    public function getContactoTable() {
        if (!$this->contactoTable) {
            $sm = $this->getServiceLocator();
            $this->contactoTable = $sm->get('Centro\Model\Logic\ContactoTable');
        }
        return $this->contactoTable;
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
                
                // 1. creamos el directorio del nuevo centro
                if(!isset($id)) {
                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('AVISO: No se pudo agregar el centro');
                    // Redireccionar a la lista de centros
                    return $this->redirect()->toRoute('centro');
                }
                FileManager::addCentroFolder($id);
                // 2. actualizamos el config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator());
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                // 3. asociacion del centro con el usuario
                $datosUsuario = Session::getUsuario($this->getServiceLocator());
                $usuarioCentro = new UsuarioCentro();
                $usuarioCentro->exchangeArray(array('usuario_id' => $datosUsuario->id, 'centro_id' => $id));
                $this->getUsuarioCentroTable()->save($usuarioCentro);
                
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
    
    public function infoAction(){
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

        $form = new UploadForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), 
                    $request->getFiles()->toArray()
            );
            
            $form->setData($data);

            if ($form->isValid()) {
                // se carga el archivo seleccionado
                $result = $form->getData(); 
                
                $validatorExtension = new Extension(array('png','jpg'));
                $validatorIsImage = new IsImage();
                $validatorImageSize = new ImageSize(array('maxWidth' => 100, 'maxHeight' => 100));
                
                $httpAdapter = new Http();
                $httpAdapter->setValidators(array($validatorExtension,$validatorImageSize,$validatorImageSize), $result['input_carga']['name']);

                if(!$httpAdapter->isValid()){
                    // mensaje de la transaccion
                    $this->flashMessenger()->addErrorMessage('No pudo cargarse la imagen, verifique extension y dimensiones no mayor a 100x100');
                    // redireccion a info del centro
                    return $this->redirect()->toRoute('centro', array('action' => 'info', 'id' => $id));
                } else {
                    // definimos path y cargamos la imagen
                    $httpAdapter->setDestination(FileManager::PATH_CENTROS.$id."/img");
                    $httpAdapter->receive($result['input_carga']['name']);
                    
                    // definimos valor del path de la imagen
                    $centro->url_imagen = FileManager::PUBLIC_PATH_CENTROS.$id."/img/".$result['input_carga']['name'];
                    
                    // se guarda la url de la imagen
                    $this->getCentroTable()->save($centro);
                    
                    // actualizamos el config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator());
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                    
                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Imagen de centro cargada con exito');
                    // redireccion a info del centro
                    return $this->redirect()->toRoute('centro', array('action' => 'info', 'id' => $id));
                }
            }
        }
        
        // obtengo los contactos asociados al centro
        $contactos = $this->getContactoTable()->getByCentroContacto($id);

        return array(
            'id'        => $id,
            'form'      => $form,
            'centro'    => $centro,
            'contactos' => $contactos,
        );
    }

}
