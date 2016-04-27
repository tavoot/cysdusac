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

class UsuarioCentroHelper extends AbstractHelper implements ServiceLocatorAwareInterface{
    protected $usuario;
    protected $usuariocentrotable;
    protected $centrotable;
    
    public function __invoke()
    {
        $this->usuario = $this->getUsuarioFromSession();
        
        if($this->usuario){
            $usuarioscentros = $this->getUsuarioCentroTable()->getCentrosPorUsuario($this->usuario->id);
            $output ="<ul class='nav nav-third-level'>";
            foreach($usuarioscentros as $usuariocentro){
            $centro = $this->getCentroTable()->get($usuariocentro->centro_id);

                if($centro){
                    $output = $output . "<li><a href='/centro/find/$usuariocentro->centro_id'>$centro->siglas</a></li>";
                }

            }
        }else{
            return $output='no hay usuarios asociados a la session';
        }
      
        $output = $output . "</ul>";
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
    
    
    
    
}