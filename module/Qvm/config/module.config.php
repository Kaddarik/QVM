<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Qvm\Controller\Indexqvm' => 'Qvm\Controller\IndexqvmController',
						'Qvm\Controller\Group' => 'Qvm\Controller\GroupController',
						'Qvm\Controller\Activity' => 'Qvm\Controller\ActivityController',
						'Qvm\Controller\User' => 'Qvm\Controller\UserController',
						'Qvmrest\Controller\Group' => 'Qvmrest\Controller\GroupController',
						'Qvmrest\Controller\Event' => 'Qvmrest\Controller\EventController',
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
						'paginationActivityDetailEvent' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/activity/detail-event/[:id]/page/[:page]',
										'constraints' => array (
												'id' => '[0-9]+',
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Activity',
												'action' => 'detailEvent',
												'page' => 1,
												'id' =>1
										)
								)
						),
						'paginationGroupIndex' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/group/index/page/[:page]',
										'constraints' => array (
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Group',
												'action' => 'index',
												'page' => 1
										)
								)
						),
						'paginationGroupListeMembres' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/group/[:id]/liste-membres/page/[:page]',
										'constraints' => array (
												'id' => '[0-9]+',
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Group',
												'action' => 'listeMembres',
												'page' => 1,
												'id' =>1
										)
								)
						),
						'paginationGroupListeActivites' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/group/[:id]/liste-activites/page/[:page]',
										'constraints' => array (
												'id' => '[0-9]+',
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Group',
												'action' => 'listeActivites',
												'page' => 1,
												'id' =>1
										)
								)
						),
						'paginationGroupRejoindre' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/group/rejoindre/page/[:page]',
										'constraints' => array (
												'page' => '[0-9]+'
										),
										'defaults' => array (
												'controller' => 'Qvm\Controller\Group',
												'action' => 'rejoindre',
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
						) ,
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
		'service_manager' => array(
				'factories' => array(
						'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
						'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
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
		'navigation' => array(
				'default' => array(
						'accueil' => array(
								'route' => 'indexqvm',
								'label' => 'Accueil',
								'pages' => array(
										'group' => array(
												'route' => 'group',
												'label' => 'Mes groupes',
												'action' => 'index',
												'pages' => array(
														'details' => array(
																'action' => 'details',
																'route' => 'group',
																//'params'     => array('id' => ),
																'label' => 'Details du groupe',
																'pages' => array(
																		'liste-membres' => array(
																				'action' => 'listeMembres',
																				'route' => 'group',
																				'label' => 'Liste des membres du groupe',
																		),
																		'liste-activites' => array(
																				'label' => 'Liste des activites du groupe',
																				'route' => 'group ',
																				'action' => 'listeActivites'
																		)
																)
														),
														'rejoindre' => array(
																'action' => 'rejoindre',
																'route' => 'group',
																'label' => 'Rejoindre un groupe'
														)
												)
										),
										'creergroupe' => array(
												'route' => 'group',
												'action' => 'create',
												'label' => 'Creer un groupe'
										),
										'activity' => array(
												'route' => 'activity',
												'action' => 'index',
												'label' => 'Mes activites',
												'pages' => array(
														'list' => array(
																'action' => 'list',
																'route' => 'activity',
																'label' => 'Liste des activites',
																'pages' => array(
																)
														),
														'detailactivity' => array(
																'action' => 'detail',
																'route' => 'activity',
																'label' => "Details de l'activite"
														),
														'listevents' => array(
																'action' => 'listEvents',
																'route' => 'activity',
																'label' => 'Liste des evenements'
														),
														'detailsevent' => array(
																'action' => 'detailEvent',
																'route' => 'activity',
																'label' => "Details de l'evenement"
														)
												)
										),
										'creeractivite' => array(
												'route' => 'activity',
												'action' => 'create',
												'label' => 'Creer une activite'
										),
										'evenementenattente' => array(
												'route' => 'activity',
												'action' => 'listPendingParticipating',
												'label' => 'Mes evenments en attente',
										),
								)
						)
				)
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'qvm' => __DIR__ . '/../view',
						'qvmrest' => __DIR__ . '/../view'
				),
				'template_map' => array (
						'pagination' => __DIR__ . '/../view/qvm/pagination.phtml' 
				) 
		) 
);
