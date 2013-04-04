<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Qvm\Controller\Indexqvm' => 'Qvm\Controller\IndexqvmController',
						'Qvm\Controller\Group' => 'Qvm\Controller\GroupController',
						'Qvm\Controller\Activity' => 'Qvm\Controller\ActivityController',
						'Qvm\Controller\User' => 'Qvm\Controller\UserController' 
				) 
		),
		'router' => array (
				'routes' => array (
						'paginationActivityList' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/activity/list/page/[:page]',
										'constraints' => array (
												'page' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Activity',
												'action' => 'list',
												'page' => 1 
										) 
								) 
						),
						'paginationActivityListEvents' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/activity/list-events/page/[:page]',
										'constraints' => array (
												'page' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Activity',
												'action' => 'listEvents',
												'page' => 1 
										) 
								) 
						),
						'paginationActivityListPendingParticipating' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/activity/list-events-participating/page/[:page]',
										'constraints' => array (
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Activity',
												'action' => 'listPendingParticipating',
												'page' => 1
										)
								)
						),
						'indexqvm' => array (
								'type' => 'segment',
								'options' => array (
										// Change this to something specific to
										// your module
										'route' => '/indexqvm[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Indexqvm',
												'action' => 'index' 
										) 
								) 
						),
						'group' => array (
								'type' => 'segment',
								'options' => array (
										// Change this to something specific to
										// your module
										'route' => '/group[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Group',
												'action' => 'index' 
										) 
								) 
						),
						'activity' => array (
								'type' => 'segment',
								'options' => array (
										// Change this to something specific to
										// your module
										'route' => '/activity[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Activity',
												'action' => 'index' 
										) 
								) 
						),
						'user' => array (
								'type' => 'segment',
								'options' => array (
										// Change this to something specific to
										// your module
										'route' => '/user[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\User',
												'action' => 'index' 
										) 
								) 
						) 
				) 
		),
		'service_manager' => array(
				'factories' => array(
						'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
				),
		),
		'translator' => array (
				'locale' => 'fr_FR',
				'translation_file_patterns' => array (
						array (
								'type' => 'gettext',
								'base_dir' => __DIR__ . '/../language',
								'pattern' => '%s.mo' 
						) 
				) 
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'qvm' => __DIR__ . '/../view' 
				),
				'template_map' => array (
						'pagination' => __DIR__ . '/../view/qvm/pagination.phtml' 
				) 
		) 
);
