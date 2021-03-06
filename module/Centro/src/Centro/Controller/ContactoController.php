<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Centro\Model\Data\Contacto;
use Centro\Form\ContactoForm;
use Centro\Util\XmlGenerator;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Util\UtilSistema as Log;


class ContactoController extends AbstractActionController {

    protected $contactoTable;
    protected $centroTable;

 
     public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }
    
   
    public function getContactoTable() {
        if (!$this->contactoTable) {
            $sm = $this->getServiceLocator();
            $this->contactoTable = $sm->get('Centro\Model\Logic\ContactoTable');
        }
        return $this->contactoTable;
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
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar',
                        'id'=>'x'
            ));
        }
        

        try { 
            //variable global
            $contactos= $this->getContactoTable()->getByCentroContacto($centro_id);
            $centro=$this->getCentroTable()->get($centro_id);
            
            return new ViewModel(array(
                'contactos'=>$contactos,  'centro'=>$centro,
            ));
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar',
                        'id'=>$centro_id,
            ));
        }
    }
    

 
    public function addAction() {
        $centro_id = (int) $this->params()->fromRoute('id', 0);
        if (!$centro_id) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'add',
                        'id' => 'x'
            ));
        }

        $form = new ContactoForm();
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $contacto= new Contacto();
                $form->setInputFilter($contacto->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()){
                    $contacto->exchangeArray($form->getData());
                    $this->getContactoTable()->save($contacto);

                    // actualizacion del config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                    // registro en el sistema que ha agregado un contacto para el centro
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_INFORMACION_GENERAL, $centro_id);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Contacto agregado satisfactoriamente');
                    return $this->redirect()->toRoute('contacto', array(
                    'action' => 'listar',
                    'id' => $centro_id,
                     ));
                }
            } else{
                // redireccion a lista de contactos del centro
                return $this->redirect()->toRoute('contacto', array(
                'action' => 'listar',
                'id' => $centro_id,
                ));
            }
            
        }
        
        $centro = $this->getCentroTable()->get($centro_id);
        return array(
            'form' => $form, 'centro' => $centro
        );
    }
    
    
    public function editAction(){
        $contacto_id = (int) $this->params()->fromRoute('id', 0);
        if (!$contacto_id) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }
        
        try {
            $contacto = $this->getContactoTable()->get($contacto_id);
            $centro = $this->getCentroTable()->get($contacto->centro_id);
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar'
            ));
        }

        $form = new ContactoForm();
        $form->bind($contacto);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $submit = $request->getPost('submit');
            if($submit=='Aceptar'){
                $form->setInputFilter($contacto->getInputFilter());
                $form->setData($request->getPost());

                if ($form->isValid()) {
                    $this->getContactoTable()->save($contacto);

                    // actualizacion del config centros.xml
                    $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                    $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);

                    // registro en el sistema que ha actualizado un contacto para el centro
                    $log = new Log($this->getServiceLocator());
                    $log->registrarCambio(Catalogo::CAMBIO_DE_INFORMACION_GENERAL, $centro->id);

                    // mensaje de la transaccion
                    $this->flashMessenger()->addInfoMessage('Contacto editado satisfactoriamente');
                    // redireccion a lista de contactos del centro
                    return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar',
                        'id'=>$contacto->centro_id,
                    ));
                    
                }
            }else{
                // redireccion a lista de contactos del centro
                return $this->redirect()->toRoute('contacto', array(
                    'action' => 'listar',
                    'id'=>$contacto->centro_id,
                ));
            }
            
        }

        return array(
            'form' => $form, 'contacto' => $contacto, 'centro' => $centro 
        );
    }
    
    public function deleteAction() {
        $contacto_id = (int) $this->params()->fromRoute('id', 0);
        if (!$contacto_id) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar',
                        'id' => 'x'
            ));
        }
        
        try {
            $contacto = $this->getContactoTable()->get($contacto_id);
            $centro = $this->getCentroTable()->get($contacto->centro_id);
            
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('contacto', array(
                        'action' => 'listar'
            ));
        }
        
        $request = $this->getRequest();
        if($request->isPost()){
            $del = $request->getPost('del', 'No');
            
            if($del == 'Si'){
                $id = (int) $request->getPost('id');
                $this->getContactoTable()->delete($id);
                
                // actualizacion del config centros.xml
                $writer = new XmlGenerator($this->getServiceLocator(), $this->getParametersUrl($request));
                $writer->writeXmlConfig(XmlGenerator::CONFIG_CENTROS);
                
                // registro en el sistema que ha removido un contacto para el centro
                $log = new Log($this->getServiceLocator());
                $log->registrarCambio(Catalogo::CAMBIO_DE_INFORMACION_GENERAL, $centro->id);

                // mensaje de la transaccion
                $this->flashMessenger()->addInfoMessage('Contacto eliminado satisfactoriamente');
            }
            
            // redireccion a lista de contactos del centro
            return $this->redirect()->toRoute('contacto', array('action' => 'listar', 'id' => $centro->id ));
        }
        
        return array(
            'id' => $contacto_id,
            'contacto' => $contacto,
            'centro' => $centro
        );
        
    }

}
