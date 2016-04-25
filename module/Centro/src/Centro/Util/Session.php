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
                $usuario = $auth->getIdentity();                
                
                $datos_usuario = $auth->getStorage()->read();
                
                var_dump($datos_usuario); 
                /*$usuario_estudiante_table = $table_provider->getTable(TableProvider::USUARIO_ESTUDIANTE_TABLE);

                //obtiene el usuario_estudiante asociado al usuario
                $usuario_estudiante = $usuario_estudiante_table->get($usuario_estudiante);

                //obtiene al estudiante asociado al usuario
                $estudiante = $table_provider
                        ->getTable(TableProvider::USUARIO_TABLE)
                        ->get($usuario_estudiante->estudiante);*/

                return $datos_usuario;
            } else {
                return NULL;
            }
    }

}
