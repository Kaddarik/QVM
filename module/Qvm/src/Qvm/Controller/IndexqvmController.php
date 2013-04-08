<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvm for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvm\Controller;

use Qvm\Model\UpcomingParticipating;

use Zend\Session\Container;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Qvm\Form\VoteEvenementForm;
use Qvm\Model\VoteKindTable;
use Qvm\Model\PendingParticipating;

class IndexqvmController extends AbstractActionController
{
	protected $eventTable;
	protected $upcomingParticipatingTable;
	protected $pendingParticipatingTable;
	protected $groupTable;
	protected $votekindTable;

	public function indexAction()
	{
		$nbLimit = (int) 5;
		
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			 
			$vote = new UpcomingParticipating();
			$form->setInputFilter($vote->getInputFilter());
			$form->setData($request->getPost());
		
			if ($form->isValid()) {
				//SAVE TO DATABASE...
			}
		}
		
		return new ViewModel(array(
            'upcomingParticipatings' => $this->getUpcomingParticipatingTable()->getUpcomingParticipatingByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), 5),
			'nbEvtEnAttente' => count($this->getPendingParticipatingTable()->getPendingParticipatingByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), null)),
			'pendingParticipatingsLimit' => $this->getPendingParticipatingTable()->getPendingParticipatingByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), 5),
			'nbLimit' => $nbLimit,
			'nbGroups' => count($this->getGroupTable()->getGroupByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), null)),
			'groupsLimit' => $this->getGroupTable()->getGroupByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), $nbLimit),
			'form' => $form
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
	
	public function getVoteKindTable()
	{
		if (!$this->votekindTable) {
			$sm = $this->getServiceLocator();
			$this->votekindTable = $sm->get('Qvm\Model\VoteKindTable');
		}
		return $this->votekindTable;
	}

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/indexqvm/foo
        return array();
    }
}
