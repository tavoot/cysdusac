<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'acl' => array(
        'roles' => array(
            'invitado'      => null,
            'usuario'       => 'invitado',
            'estandar'      => 'usuario',
            'administrador' => 'usuario',
        ),
        'resources' => array(
            'allow' => array(
                'Centro\Controller\Acceso' => array(
                    'index'     => 'invitado',
                    'login'     => 'invitado',
                    'logout'    => 'usuario',
                ),
                'Centro\Controller\Canal' => array(
                    'listar'    => 'estandar',
                    'add'       => 'estandar',
                    'edit'      => 'estandar',
                    'delete'    => 'estandar',
                ),
                'Centro\Controller\Centro' => array(
                    'index'     => 'administrador',
                    'add'       => 'administrador',
                    'edit'      => 'administrador',
                    'delete'    => 'administrador',
                    'relaciger' => 'administrador',
                    'find'      => 'administrador',
                    'info'      => 'estandar',
                    'inicio'    => 'usuario',
                ),
                'Centro\Controller\Contacto' => array(
                    'listar'    => 'estandar',
                    'add'       => 'estandar',
                    'edit'      => 'estandar',
                    'delete'    => 'estandar',
                ),
                'Centro\Controller\Item' => array(
                    'listar'    => 'estandar',
                    'add'       => 'estandar',
                    'edit'      => 'estandar',
                    'delete'    => 'estandar',
                ),
                'Centro\Controller\UsuarioCentro' => array(
                    'find'      => 'administrador',
                    'delete'    => 'administrador',
                ),
                'Centro\Controller\Usuario' => array(
                    'index'     => 'administrador',
                    'add'       => 'administrador',
                    'edit'      => 'administrador',
                    'delete'    => 'administrador',
                ),
                'Centro\Controller\Version' => array(
                    'create'     => 'administrador',
                ),
            ),
            'deny' => array(
                'Centro\Controller\Usuario' => array(
                    'login'     => 'usuario',
                ),
            ),
        ),
    ),
);