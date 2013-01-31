<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvm for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvm;

use Qvm\Model\Group;

use Qvm\Model\GroupTable;

use Qvm\Model\UpcomingParticipatingTable;

use Qvm\Model\UpcomingParticipating;

use Qvm\Model\PendingParticipatingTable;

use Qvm\Model\PendingParticipating;

use Qvm\Model\EventTable;

use Qvm\Model\Event;

use Qvm\Model\ActivityTable;

use Qvm\Model\Activity;




use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface
{
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

    public function onBootstrap($e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
    			),
    	);
    }
}