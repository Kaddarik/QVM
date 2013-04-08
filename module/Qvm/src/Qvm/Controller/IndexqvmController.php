<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Qvm for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Qvm\Controller;


use Qvm\Model\Participation;

use Qvm\Model\VoteKind;
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
	protected $participationTable;

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
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$id_event = (int) $this->params()->fromRoute('id', 0);
			$form->setData ($request->getPost ());
			$vote = $form->get ( 'voteEvenement' )->getValue();
			$this->getParticipationTable()->saveParticipation($id_event, 1, 1);
			return array();
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
	
	public function majVoteAction()
	{
		$id_event = (int) $this->params()->fromRoute('id', 0);
		$vote = (int) $this->params()->fromRoute('vote', 0);
		if (!$id_event) {
			return array();
		}
		
		$form  = new VoteEvenementForm();
		$request = $this->getRequest ();
		$form->setData ($request->getPost ());
			
		/*$vote = $form->get ( 'voteEvenement' )->getValue();*/
		if (!$id_event) {
			return array();
		}
		// Changer vote
		$this->getParticipationTable()->saveParticipation($id_event, 1, $vote);
		return $this->redirect()->toRoute('indexqvm');
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

	public function getParticipationTable()
	{
		if (!$this->participationTable) {
			$sm = $this->getServiceLocator();
			$this->participationTable = $sm->get('Qvm\Model\ParticipationTable');
		}
		return $this->participationTable;
	}

}
