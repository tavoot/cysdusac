<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Helper;


use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Centro\Util\Session;
use Centro\Util\CatalogoValor as Catalogo;
use Centro\Model\Data\Canal as Canal;

class CanalCentroHelper extends AbstractHelper implements ServiceLocatorAwareInterface{
    protected $usuario;
    protected $usuariocentrotable;
    protected $centrotable;
    protected $canalTable;
    
    public function __invoke($url)
    {
        $this->usuario = $this->getUsuarioFromSession();
        
        if($this->usuario){
            $usuarioscentros = $this->getUsuarioCentroTable()->getByUsuario($this->usuario->id);
            $output='';
            foreach($usuarioscentros as $usuariocentro){
            $centro = $this->getCentroTable()->get($usuariocentro->centro_id);
                if($centro){
                    //solo deberia de existir un canal interno para cualquier centro
                    $canal_id=0;
                    $canales = $this->getCanalTable()->getByCentroCanal($centro->id, Catalogo::INTERNO);
                    
                    foreach($canales as $canal){
                        $canal_id = $canal->id;
                    }
                   
                    //var_dump($canal_id);
                    $output = $output . "<a href='#'>$centro->siglas<span class='fa arrow'></span</a>";
                    $output = $output . "<ul class='nav nav-third-level'>";
                    $output = $output . "<li><a href='$url/centro/edit/$centro->id'>Informacion General</a></li>";
                    $output = $output . "<li><a href='$url/item/listar/$canal_id'>Canale RSS Interno</a></li>";
                    $output = $output . "<li><a href='$url/canal/listar/$centro->id'>Canales RSS Externo</a></li></ul>";
                }
            }
            
        }else{
            return $output='no hay usuarios asociados a la session';
        }
        return $output;
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
     public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
   
    private function getUsuarioFromSession() {
        if ($this->usuario) {
            return $this->usuario;
        } else {
            $sm = $this->getServiceLocator()->getServiceLocator();
            return Session::getUsuario($sm);
        }
    }
    
    public function getUsuarioCentroTable() {
        if (!$this->usuariocentrotable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->usuariocentrotable = $sm->get('Centro\Model\Logic\UsuarioCentroTable');
        }
        return $this->usuariocentrotable;
    }
    
    public function getCentroTable() {
        if (!$this->centrotable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->centrotable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centrotable;
    }
    
    public function getCanalTable() {
        if (!$this->canalTable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->canalTable = $sm->get('Centro\Model\Logic\CanalTable');
        }
        return $this->canalTable;
    }
    
    
    
    
}