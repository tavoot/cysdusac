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
                    'listar'    => 'usuario',
                    'add'       => 'usuario',
                    'edit'      => 'usuario',
                    'delete'    => 'usuario',
                ),
                'Centro\Controller\Centro' => array(
                    'index'     => 'administrador',
                    'add'       => 'administrador',
                    'edit'      => 'usuario',
                    'delete'    => 'administrador',
                    'relaciger' => 'administrador',
                    'find'      => 'administrador',
                    'info'      => 'usuario',
                    'inicio'    => 'usuario',
                ),
                'Centro\Controller\Contacto' => array(
                    'listar'    => 'usuario',
                    'add'       => 'usuario',
                    'edit'      => 'usuario',
                    'delete'    => 'usuario',
                ),
                'Centro\Controller\Item' => array(
                    'listar'    => 'usuario',
                    'add'       => 'usuario',
                    'edit'      => 'usuario',
                    'delete'    => 'usuario',
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
                    'editpass'  => 'administrador',
                    'perfil'    => 'usuario',
                    'cambiarpass' => 'usuario',
                ),
                'Centro\Controller\Version' => array(
                    'view'     => 'administrador',
                    'add'       =>  'administrador',
                    'listar'    =>  'administrador',
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