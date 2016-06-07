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
use Centro\Util\CatalogoValor;
/**
 * Description of MenuHelper
 *
 * @author nando
 */
class MenuHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
    
    protected $usuario;
    protected $usuarioCentroTable;
    protected $centroTable;
    protected $canalTable;
    
    public function __invoke($url) {
        $output = '';
        
        $this->usuario = $this->getUsuarioFromSession();
        
        if($this->usuario) {
            switch ($this->usuario->tipo) {
                case CatalogoValor::ESTANDAR:
                    $usuarioscentros = $this->getUsuarioCentroTable()->getByUsuario($this->usuario->id);
                    
                    // menu centros
                    //$output = '';
                    $output = '<li>';
                    $output .= '<a href="#"><i class="fa fa-th fa-fw"></i> Centros<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    //$output .= '<li>';
                    
                    foreach($usuarioscentros as $usuariocentro){
                    $centro = $this->getCentroTable()->get($usuariocentro->centro_id);
                        if($centro){
                            //solo deberia de existir un canal interno para cualquier centro
                            $canal_id=0;
                            $canales = $this->getCanalTable()->getByCentroCanal($centro->id, CatalogoValor::INTERNO);

                            foreach($canales as $canal){
                                $canal_id = $canal->id;
                            }

                            //var_dump($canal_id);
                            $output .= '<li>';
                            $output = $output . "<a href='#'>$centro->siglas<span class='fa arrow'></span></a>";
                            $output = $output . "<ul class='nav nav-third-level'>";
                            $output = $output . "<li><a href='$url/centro/info/$centro->id'>Informacion General</a></li>";
                            $output = $output . "<li><a href='$url/contacto/listar/$centro->id'>Contactos</a></li>";
                            $output = $output . "<li><a href='$url/item/listar/$canal_id'>Canales RSS Interno</a></li>";
                            $output = $output . "<li><a href='$url/canal/listar/$centro->id'>Canales RSS Externo</a></li></ul>";
                            $output .= '</li>';
                        }
                    }
                    
                    //$output .= '</li>';
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    // menu usuario
                    $output .= '<li>';
                    $output .= '<a href="#"><i class="fa fa-user fa-fw"></i> Usuarios<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    $output .= '<li><a href="'.$url.'/usuario/perfil/'.$this->usuario->id.'">Perfil</a></li>';
                    $output .= '<li><a href="'.$url.'/acceso/logout">Logout</a></li>';
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    break;
                case CatalogoValor::ADMINISTRATIVO:
                    $listaCentros = $this->getCentroTable()->fetchCentros();
                    
                    // menu administracion
                    $output = '<li>';
                    $output .= '<a href="#"><i class="fa fa-gear fa-fw"></i> Administraci&oacute;n<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    $output .= '<li><a href="'.$url.'/centro/index">Centros</a></li>';
                    $output .= '<li><a href="'.$url.'/usuario/index">Usuarios</a></li>';
                    $output .= '<li>';
                    $output .= '<a href="#"> Usuarios - Centros<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-third-level">';
                    
                    foreach ($listaCentros as $centro){
                        if($centro){
                            $output .=  '<li><a href="'.$url.'/usuariocentro/find/'.$centro->id.'">'.$centro->siglas.'</a></li>';
                        }
                    }
                    
                    $output .= '</ul>';
                    $output .= '</li>';
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    // menu relaciger 
                    $output .= '<li>';
                    $output .= '<a href="#"><i class="fa fa-pencil fa-fw"></i> Relaciger<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    $output .= '<li>';
                    $output .= '<a href="'.$url.'/centro/relaciger">Informaci&oacute;n General</a>';
                    $output .= '</li>';
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    $listaCentros = $this->getCentroTable()->fetchCentros();
                    
                    // menu centros
                    $output .= '<li>';
                    $output .= '<a href="#"><i class="fa fa-th fa-fw"></i> Centros<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    
                    foreach($listaCentros as $centro){
                        if($centro){
                            //solo deberia de existir un canal interno para cualquier centro
                            $canal_id=0;
                            $canales = $this->getCanalTable()->getByCentroCanal($centro->id, CatalogoValor::INTERNO);

                            foreach($canales as $canal){
                                $canal_id = $canal->id;
                            }

                            //var_dump($canal_id);
                            $output .= '<li>';
                            $output = $output . "<a href='#'>$centro->siglas<span class='fa arrow'></span></a>";
                            $output = $output . "<ul class='nav nav-third-level'>";
                            $output = $output . "<li><a href='$url/centro/info/$centro->id'>Informacion General</a></li>";
                            $output = $output . "<li><a href='$url/contacto/listar/$centro->id'>Contactos</a></li>";
                            $output = $output . "<li><a href='$url/item/listar/$canal_id'>Canales RSS Interno</a></li>";
                            $output = $output . "<li><a href='$url/canal/listar/$centro->id'>Canales RSS Externo</a></li></ul>";
                            $output .= '</li>';
                        }
                    }
                    
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    // menu mantenimiento
                    $output .= '<li>';
                    $output .= '<a href="'.$url.'/version/add"><i class="fa fa-wrench fa-fw"></i> Mantenimiento</a>';
                    $output .= '</li>';
                    
                    // menu usuario
                    $output .= '<li>';
                    $output .= '<a href="#"><i class="fa fa-user fa-fw"></i> Usuarios<span class="fa arrow"></span></a>';
                    $output .= '<ul class="nav nav-second-level">';
                    $output .= '<li><a href="'.$url.'/usuario/perfil/'.$this->usuario->id.'">Perfil</a></li>';
                    $output .= '<li><a href="'.$url.'/acceso/logout">Logout</a></li>';
                    $output .= '</ul>';
                    $output .= '</li>';
                    
                    break;
                default:
                    break;
            }
        } else {
            $output = 'No existe usuario asociado a la sesion';
            return $output;
        }
        
        return $output;
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
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
        if (!$this->usuarioCentroTable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->usuarioCentroTable = $sm->get('Centro\Model\Logic\UsuarioCentroTable');
        }
        return $this->usuarioCentroTable;
    }
    
    public function getCentroTable() {
        if (!$this->centroTable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
        }
        return $this->centroTable;
    }
    
    public function getCanalTable() {
        if (!$this->canalTable) {
            $sm = $this->getServiceLocator()->getServiceLocator();
            $this->canalTable = $sm->get('Centro\Model\Logic\CanalTable');
        }
        return $this->canalTable;
    }
    
    
}
