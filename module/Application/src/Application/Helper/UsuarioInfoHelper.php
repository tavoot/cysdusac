<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Centro\Util\Session;
/**
 * Description of UsuarioInfoHelper
 *
 * @author nando
 */
class UsuarioInfoHelper extends AbstractHelper implements ServiceLocatorAwareInterface {

    protected $usuario;

    public function __invoke() {
        $output = '';
        
        $this->usuario = $this->getUsuarioFromSession();
        
        $output .= '<h4><span class="label label-primary">Bienvenido,  '.strtoupper($this->usuario->usuario).'</span></h4>';
        return $output;
    }


    private function getUsuarioFromSession() {
        if ($this->usuario) {
            return $this->usuario;
        } else {
            $sm = $this->getServiceLocator()->getServiceLocator();
            return Session::getUsuario($sm);
        }
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}
