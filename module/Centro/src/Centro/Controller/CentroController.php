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
use Zend\Validator\File\ImageSize;
use Application\Validator\ImageDimension;
use Zend\Filter\File\Rename;
use Centro\Model\Data\Canal;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\UtilSistema as Log;



class CentroController extends AbstractActionController {

    const RELACIGER = 1;
    
    protected $contactoTable;
    protected $centroTable;
    protected $canalTable;
    protected $usuario;
    protected $usuariocentroTable;
    protected $cambioTable;
    

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

    public function addAction(){
        $form = new CentroForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $centro = new Centro();
                $form->setInputFilter($centro->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()){
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
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);



                    // 3. asociacion del centro con el usuario
                    $datosUsuario = Session::getUsuario($this->getServiceLocator());
                    $usuarioCentro = new UsuarioCentro();
                    $usuarioCentro->exchangeArray(array('usuario_id' => $datosUsuario->id, 'centro_id' => $id));
                    $this->getUsuarioCentroTable()->save($usuarioCentro);

                    // 4.Al nuevo centro creado le agrego su respectivo canal interno
                    $serverUrl = sprintf('%s://%s', $request->getUri()->getScheme(), $request->getUri()->getHost());
                    $urlCanalInterno = $serverUrl.$request->getBasePath().'/'.FileManager::PUBLIC_PATH_CENTROS.$id.'/canal/canalrss.xml';
                    $canal = new Canal();
                    $canal->exchangeArray(array('tipo'=> Catalogo::INTERNO,  'centro_id'=>$id, 'secuencia'=>0, 'enlace'=> $urlCanalInterno));
                    $this->getCanalTable()->save($canal);

                    // 5. Agrego el nuevo cambio que sufre el sistema a la base de datos
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::AGREGAR_CENTRO, $id);

                    // 6. crear el archivo de estadistico del canal interno
                    FileManager::addCanalFile($id, 0);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Centro agregado satisfactoriamente');
                    // Redireccionar a la lista de centros
                    return $this->redirect()->toRoute('centro');
                }
            } else { 
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
            $urlImagen = $centro->url_imagen;
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('centro', array(
                        'action' => 'index'
            ));
        }

        $form = new CentroForm();
        $form->bind($centro);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $form->setInputFilter($centro->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $centro->url_imagen = $urlImagen;

                    $this->getCentroTable()->save($centro);

                    // actualizamos el config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                    // registro en el sistema que ha habido un cambio en la informacion general del centro
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_INFORMACION_GENERAL, $id);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Centro editado satisfactoriamente');

                    // dependiendo del tipo de usuario redirige a distinta pagina
                    $datosUsuario = Session::getUsuario($this->getServiceLocator());
                    // Redirect to info
                    return $this->redirect()->toRoute('centro', array('action' => 'info', 'id' => $id));
                }
             }
             else{
                // Redirect to info
                return $this->redirect()->toRoute('centro', array('action' => 'info', 'id' => $id));
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
                $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // agregamos un registro de cambios al sistema con la opcion de eliminar
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::ELIMINAR_CENTRO, $id);
                
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
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $form->setInputFilter($centro->getInputFilter());
                $form->setValidationGroup('id', 'nombre', 'siglas', 'sitio_web', 'mision', 'vision', 'descripcion');
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    // actualizacion de los campos enviados
                    $relaciger->nombre = $centro->nombre;
                    $relaciger->siglas = $centro->siglas;
                    $relaciger->sitio_web = $centro->sitio_web;
                    $relaciger->mision = $centro->mision;
                    $relaciger->vision = $centro->vision;
                    $relaciger->descripcion = $centro->descripcion;

                    // guardo los cambios
                    $this->getCentroTable()->save($relaciger);
                    // actualizamos el config relaciger.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_RELACIGER);
                    
                    // agregamos un registro de cambios al sistema con la opcion de eliminar
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_INFORMACION_GENERAL, self::RELACIGER);
                    
                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('RELACIGER - Informacion general guardada satisfactoriamente');
                    // ser redirecciona a relaciger para visualizar el mensaje de la transaccion
                    return $this->redirect()->toRoute('centro', array('action' => 'relaciger'));
                }
            } else {
                // Redireccionar a la lista de centros
                return $this->redirect()->toRoute('centro', array('action' => 'inicio'));
            }
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
                $validatorDimension = new ImageDimension();
                $validatorImageSize = new ImageSize(array('minWidth' => 500, 'minHeight' => 500));

                $httpAdapter = new Http();
                $httpAdapter->setValidators(array($validatorExtension,$validatorDimension,$validatorImageSize), $result['input_carga']['name']);

                if(!$httpAdapter->isValid()){
                    // mensaje de la transaccion
                    $this->flashMessenger()->addErrorMessage('No pudo cargarse la imagen, verifique extension y dimensiones no menor a 500x500');
                    // redireccion a info del centro
                    return $this->redirect()->toRoute('centro', array('action' => 'info', 'id' => $id));
                } else {
                    // definimos path y cargamos la imagen
                    $pathImageTarget = FileManager::PATH_CENTROS.$id."/img";
                    $httpAdapter->setDestination($pathImageTarget);
                    $httpAdapter->receive($result['input_carga']['name']);

                    // renombramos la imagen cargada
                    $extension = pathinfo($pathImageTarget."/".$result['input_carga']['name'], PATHINFO_EXTENSION);
                    $newFileName = "imagen.".strtolower($extension);

                    $filterRename = new Rename(array(
                        'target' => $pathImageTarget."/".$newFileName,
                        'overwrite' => true,
                    ));
                    $filterRename->filter($pathImageTarget."/".$result['input_carga']['name']);

                    // definimos valor del path de la imagen
                    $centro->url_imagen = FileManager::PUBLIC_PATH_CENTROS.$id."/img/".$newFileName;

                    // se guarda la url de la imagen
                    $this->getCentroTable()->save($centro);

                    // actualizamos el config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                    // registro en el sistema, que ha habudo un cambio de imagen
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_IMAGEN_CENTRO, $id);

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
    
    public function soporteAction(){
        $log = new Log($this->getServiceLocator());
        $log->registrarVersion();
        
    }

    
    public function inicioAction(){
        // pantalla de inicio para ambos usuarios
        return new ViewModel();
    }
    
}
