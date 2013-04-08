<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvm for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvm;

use Qvm\Model\VoteKind;
use Qvm\Model\VoteKindTable;
use Qvm\Model\ActivityCategory;
use Qvm\Model\ActivityCategoryTable;
use Qvm\Model\ActivityAdmin;
use Qvm\Model\ActivityAdminTable;
use Qvm\Model\Category;
use Qvm\Model\CategoryTable;
use Qvm\Model\Comment;
use Qvm\Model\CommentTable;
use Qvm\Model\Group;
use Qvm\Model\GroupTable;
use Qvm\Model\Preference;
use Qvm\Model\PreferenceTable;
use Qvm\Model\UpcomingParticipatingTable;
use Qvm\Model\UpcomingParticipating;
use Qvm\Model\PendingParticipatingTable;
use Qvm\Model\PendingParticipating;
use Qvm\Model\AllEventsTable;
use Qvm\Model\AllEvents;
use Qvm\Model\EventTable;
use Qvm\Model\Event;
use Qvm\Model\ActivityTable;
use Qvm\Model\Activity;
use Qvm\Model\PersonTable;
use Qvm\Model\Person;
use Qvm\Model\GroupMemberTable;
use Qvm\Model\GroupMember;
use Qvm\Model\ParticipatingGroupTable;
use Qvm\Model\ParticipatingGroup;
use Qvm\Model\PendingTable;
use Qvm\Model\Pending;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface
{
	public function onBootstrap(MvcEvent $e)
	{
		$e->getApplication()->getServiceManager()->get('translator');
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
	}
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Qvm\Model\EventTable' =>  function($sm) {
    						$tableGateway = $sm->get('EventTableGateway');
    						$table = new EventTable($tableGateway);
    						return $table;
    					},
    					'EventTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Event());
    						return new TableGateway('event', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\UpcomingParticipatingTable' =>  function($sm) {
    						$tableGateway = $sm->get('UpcomingParticipatingTableGateway');
    						$table = new UpcomingParticipatingTable($tableGateway);
    						return $table;
    					},
    					'PendingParticipatingTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new PendingParticipating());
    						return new TableGateway('pendingparticipating', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\PendingParticipatingTable' =>  function($sm) {
    						$tableGateway = $sm->get('PendingParticipatingTableGateway');
    						$table = new PendingParticipatingTable($tableGateway);
    						return $table;
    					},   					   					
    					'UpcomingParticipatingTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new UpcomingParticipating());
    						return new TableGateway('upcomingparticipating', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\GroupTable' =>  function($sm) {
    						$tableGateway = $sm->get('GroupTableGateway');
    						$table = new GroupTable($tableGateway);
    						return $table;
    					},
    					'GroupTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Group());
    						return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\ActivityTable' =>  function($sm) {
    						$tableGateway = $sm->get('ActivityTableGateway');
    						$table = new ActivityTable($tableGateway);
    						return $table;
    					},
    					'ActivityTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Activity());
    						return new TableGateway('activity', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\UserTable' =>  function($sm) {
    						$tableGateway = $sm->get('UsersTableGateway');
    						$table = new UsersTable($tableGateway);
    						return $table;
    					},
    					'UserTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new User());
    						return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\GroupMemberTable' =>  function($sm) {
    						$tableGateway = $sm->get('GroupMemberTableGateway');
    						$table = new GroupMemberTable($tableGateway);
    						return $table;
    					},
    					'GroupMemberTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new GroupMember());
    						return new TableGateway('groupmember', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\ActivityTable' =>  function($sm) {
    						$tableGateway = $sm->get('ActivityTableGateway');
    						$table = new ActivityTable($tableGateway);
    						return $table;
    					},
    					'ActivityTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Activity());
    						return new TableGateway('activity', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\CategoryTable' =>  function($sm) {
    						$tableGateway = $sm->get('CategoryTableGateway');
    						$table = new CategoryTable($tableGateway);
    						return $table;
    					},
    					'CategoryTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Category());
    						return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\ActivityCategoryTable' =>  function($sm) {
    						$tableGateway = $sm->get('ActivityCategoryTableGateway');
    						$table = new ActivityCategoryTable($tableGateway);
    						return $table;
    					},
    					'ActivityCategoryTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new ActivityCategory());
    						return new TableGateway('activityCategory', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\ActivityAdminTable' =>  function($sm) {
    						$tableGateway = $sm->get('ActivityAdminTableGateway');
    						$table = new ActivityAdminTable($tableGateway);
    						return $table;
    					},
    					'ActivityAdminTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new ActivityAdmin());
    						return new TableGateway('activityAdmin', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\AllEventsTable' =>  function($sm) {
    						$tableGateway = $sm->get('AllEventsTableGateway');
    						$table = new AllEventsTable($tableGateway);
    						return $table;
    					},
    					'AllEventsTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new AllEvents());
    						return new TableGateway('allEvents', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\VoteKindTable' =>  function($sm) {
    						$tableGateway = $sm->get('VoteKindTableGateway');
    						$table = new VoteKindTable($tableGateway);
    						return $table;
    					},
    					'VoteKindTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new VoteKind());
    						return new TableGateway('votekind', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\ParticipatingGroupTable' =>  function($sm) {
    						$tableGateway = $sm->get('ParticipatingGroupTableGateway');
    						$table = new ParticipatingGroupTable($tableGateway);
    						return $table;
    					},
    					'ParticipatingGroupTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new ParticipatingGroup());
    						return new TableGateway('participatinggroup', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\PreferenceTable' =>  function($sm) {
    						$tableGateway = $sm->get('PreferenceTableGateway');
    						$table = new PreferenceTable($tableGateway);
    						return $table;
    					},
    					'PreferenceTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Preference());
    						return new TableGateway('preference', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\CommentTable' =>  function($sm) {
    						$tableGateway = $sm->get('CommentTableGateway');
    						$table = new CommentTable($tableGateway);
    						return $table;
    					},
    					'CommentTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Comment());
    						return new TableGateway('comment', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Qvm\Model\PendingTable' =>  function($sm) {
    						$tableGateway = $sm->get('PendingTableGateway');
    						$table = new PendingTable($tableGateway);
    						return $table;
    					},
    					'PendingTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Pending());
    						return new TableGateway('pending', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    	);
    }
}
