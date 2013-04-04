<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvmrest for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvmrest;

//use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
//use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;


class Module implements BootstrapListenerInterface
{
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
						),
				'Zend\Loader\StandardAutoloader' => array(
					'namespaces' => array(
						__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
					),
				),
			);
	}
	
	public function registerJsonStrategy(EventInterface $e)
	{
		$app = $e->getTarget();
		$locator = $app->getServiceManager();
		$view = $locator->get('Zend\View\View');
		$jsonStrategy = $locator->get('ViewJsonStrategy');
		
		// Attach strategy, wich is a listener agrregate, at high priority
		$view->getEventManager()->attach($jsonStrategy, 100);
	}
	
	public function onBootstrap(EventInterface $e)
	{
		$app = $e->getApplication();
		$app->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
		$em = $app->getEventManager()->getSharedManager();
		$sm = $app->getServiceManager();
		
		$em->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) use ($sm){
			$strategy = $sm->get('ViewJsonStrategy');
			$view = $sm->get('ViewManager')->getView();
			$strategy->attach($view->getEventManager());
		});
	}
}


/*class Module implements AutoloaderProviderInterface
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
}*/
