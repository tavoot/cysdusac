<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

use Centro\Model\Data\Usuario;
//use Centro\Util\TableProvider;

class Session {

    /**
     * obtiene el usuario asociado a la session actual
     * 
     * @return Usuario
     */
    public static function getUsuario($sm) {
            $auth = $sm->get('Zend\Authentication\AuthenticationService');

            if ($auth->hasIdentity()) {
                //obtiene el codigo del usuario logeado
                //$usuario = $auth->getIdentity();                
                
                $datos_usuario = $auth->getStorage()->read();
                return $datos_usuario;
            } else {
                return NULL;
            }
    }

}
