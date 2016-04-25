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

use Centro\Model\Logic\UsuarioCentroTable;
use Centro\Model\Data\UsuarioCentro;

use Centro\Util\Session;

class UsuarioCentroHelper extends AbstractHelper implements ServiceLocatorAwareInterface{
    protected $usuario;
    protected $usuariocentrotable;
    protected $centrotable;
    
    public function __invoke()
    {
        $this->getUsuarioFromSession();
        
        $usuarioscentros = $this->getUsuarioCentroTable()->getCentrosPorUsuario(2);
        $output ="<ul class='nav nav-third-level'>";
        foreach($usuarioscentros as $usuariocentro){
        $centro = $this->getCentroTable()->get($usuariocentro->centro_id);
            
            if($centro){
                $output = $output . "<li><a href='/centro/find/$usuariocentro->centro_id'>$centro->siglas</a></li>";
            }
                                        
        }
        /*$this->usuario = $this->getUsuarioFromSession();
        $output = $this->usuario . ":)";*/
        
        
        //$this->count++;
        //$this->getUsuarioCentroTable()->getCentrosPorUsuario(1);
        
        //$output = sprintf("I have seen 'The Jerk' %d time(s).", $this->count);
        $output = $output . "</ul>";
        return $output;
        //return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
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
            //$sm = $application->getServiceManager();
            //$serviceLocator = $this->getServiceLocator();
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