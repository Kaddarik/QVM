<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Qvm\Controller\Indexqvm' => 'Qvm\Controller\IndexqvmController',
        	'Qvm\Controller\Group' => 'Qvm\Controller\GroupController',
        	'Qvm\Controller\Activity' => 'Qvm\Controller\ActivityController',
        	'Qvm\Controller\User' => 'Qvm\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'indexqvm' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/indexqvm[/:action][/:id]',
                    'constraints' => array(
                    	'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'id' => '[0-9]+',
                    ),
                	'defaults' => array(
                        'controller'    => 'Qvm\Controller\Indexqvm',
                        'action'        => 'index',
                    ),
                ),
            ),
            'group' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/group[/:action][/:id]',
                    'constraints' => array(
                    	'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'id' => '[0-9]+',
                    ),
                	'defaults' => array(
                        'controller'    => 'Qvm\Controller\Group',
                        'action'        => 'index',
                    ),
                ),
            ),
            'activity' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/activity[/:action][/:id]',
                    'constraints' => array(
                    	'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'id' => '[0-9]+',
                    ),
                	'defaults' => array(
                        'controller'    => 'Qvm\Controller\Activity',
                        'action'        => 'index',
                    ),
                ),
            ),
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                    	'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'id' => '[0-9]+',
                    ),
                	'defaults' => array(
                        'controller'    => 'Qvm\Controller\User',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'qvm' => __DIR__ . '/../view',
        ),
    ),
);
