<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Qvmrest\Controller\Group' => 'Qvmrest\Controller\GroupController',
			'Qvmrest\Controller\Event' => 'Qvmrest\Controller\EventController',
		),
	),
	'router' => array(
		'routes' => array(
			'rest-group' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '/rest/group[/:id]',
					'constraints' => array(
						'id'=> '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Qvmrest\Controller\Group',
					)
				)
			),
			'rest-event' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '/rest/event[/:id]',
					'constraints' => array(
						'id'=> '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Qvmrest\Controller\Event',
					)
				)
			)
		)
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'qvmrest' => __DIR__ . '/../view',
		),
	),
);



/*return array(
    'controllers' => array(
        'invokables' => array(
            'Qvmrest\Controller\Qvmrest' => 'Qvmrest\Controller\QvmrestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'module-name-here' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/module-specific-root',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Qvmrest\Controller',
                        'controller'    => 'Qvmrest',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Qvmrest' => __DIR__ . '/../view',
        ),
    ),
);*/
