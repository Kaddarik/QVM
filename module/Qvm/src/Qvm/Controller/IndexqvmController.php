<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvm for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvm\Controller;

use Zend\Session\Container;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexqvmController extends AbstractActionController
{
	protected $eventTable;
	protected $upcomingParticipatingTable;
	protected $pendingParticipatingTable;
	protected $groupTable;

	public function indexAction()
	{
		return new ViewModel(array(
            'upcomingParticipatings' => $this->getUpcomingParticipatingTable()->fetchAll(),
			'pendingParticipatings' => $this->getPendingParticipatingTable()->fetchAll(),
			'groups' => $this->getGroupTable()->fetchAll(),
        ));
	}
	
	public function loginAction()
	{
		$user_session = new Container('user');
		$user_session->userid='1';
		
		return $this->redirect()->toRoute('index');
	}
	
	/*public function getEventTable()
	{
		if (!$this->eventTable) {
			$sm = $this->getServiceLocator();
			$this->eventTable = $sm->get('Qvm\Model\EventTable');
		}
		return $this->eventTable;
	}*/
	
	public function getGroupTable()
	{
		if (!$this->groupTable) {
			$sm = $this->getServiceLocator();
			$this->groupTable = $sm->get('Qvm\Model\GroupTable');
		}
		return $this->groupTable;
	}
	
	public function getUpcomingParticipatingTable()
	{
		if (!$this->upcomingParticipatingTable) {
			$sm = $this->getServiceLocator();
			$this->upcomingParticipatingTable = $sm->get('Qvm\Model\UpcomingParticipatingTable');
		}
		return $this->upcomingParticipatingTable;
	}
	
	public function getPendingParticipatingTable()
	{
		if (!$this->pendingParticipatingTable) {
			$sm = $this->getServiceLocator();
			$this->pendingParticipatingTable = $sm->get('Qvm\Model\PendingParticipatingTable');
		}
		return $this->pendingParticipatingTable;
	}

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/indexqvm/foo
        return array();
    }
}