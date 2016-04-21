<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Centro\Controller\Centro' => 'Centro\Controller\CentroController',
            'Centro\Controller\Usuario' => 'Centro\Controller\UsuarioController',
            'Centro\Controller\Canal' => 'Centro\Controller\CanalController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'centro' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/centro[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Centro\Controller\Centro',
                        'action' => 'index',
                    ),
                ),
            ),
            'canal' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/canal[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Centro\Controller\Canal',
                        'action' => 'index',
                    ),
                ),
            ),
            
            
            // The following section is new and should be added to your file
            'usuario' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/usuario',
                    'defaults' => array(
                        'controller' => 'Centro\Controller\Usuario',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Centro\Controller\Usuario',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'centro' => __DIR__ . '/../view',
            'usuario' => __DIR__ . '/../view',
            'canal' => __DIR__ . '/../view',
        ),
    ),
);
