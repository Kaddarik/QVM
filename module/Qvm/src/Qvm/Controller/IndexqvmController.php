<?php

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

/**
 * @author Margot Bernard / Antoine Dumas / Sebastien Gendreau / Cedric Bouygues
 *
 */
class IndexqvmController extends AbstractActionController
{
	protected $eventTable;
	protected $upcomingParticipatingTable;
	protected $pendingParticipatingTable;
	protected $groupTable;
	protected $votekindTable;
	protected $participationTable;

	/**
	 * indexAction
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction()
	{	
		if (!$this->zfcUserAuthentication()->hasIdentity()) {
		
		
			// Use ZfcUser's login action rather than its authentication
			// action.
			return $this->forward()->dispatch('zfcuser', array(
					'action' => 'login'
			));
		}
		
		
		//Récupération de l'id de l'utilisateur connecté
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Instantiation du nombre limite de groupes
		$nbLimit = (int) 5;
		
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();

		//Pour chaque entrée présente dans la table votekindtable on rempli un array 
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'élément select du formulaire de vote avec l'array 
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		//Récupération de la requête
		$request = $this->getRequest();
		if ($request->isPost()) {
			//Recuperation de l'id de l'event
			//$id_event = (int) $this->params()->fromRoute('id', 0);
			//$participation = new Participation();
			//$form->setInputFilter($participation->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid()) {
				var_dump($form->getData());
				$id_event = (int) $form->get ( 'id_event' )->getValue();
				$vote = (int) $form->get ( 'voteEvenement' )->getValue();
				$this->getParticipationTable()->updateParticipation($id_event, $vote, $user_id);
				return $this->redirect ()->toRoute ( 'indexqvm' );
			}
		}
		
		//Construction de la vue
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
	
	/**
	 * Getter du model GroupTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\GroupTable>
	 */
	public function getGroupTable()
	{
		if (!$this->groupTable) {
			$sm = $this->getServiceLocator();
			$this->groupTable = $sm->get('Qvm\Model\GroupTable');
		}
		return $this->groupTable;
	}
	
	/**
	 * Getter du model UpcomingParticipatingTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\UpcomingParticipatingTable>
	 */
	public function getUpcomingParticipatingTable()
	{
		if (!$this->upcomingParticipatingTable) {
			$sm = $this->getServiceLocator();
			$this->upcomingParticipatingTable = $sm->get('Qvm\Model\UpcomingParticipatingTable');
		}
		return $this->upcomingParticipatingTable;
	}
	
	/**
	 * Getter du model PendingParticipatingTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\PendingParticipatingTable>
	 */
	public function getPendingParticipatingTable()
	{
		if (!$this->pendingParticipatingTable) {
			$sm = $this->getServiceLocator();
			$this->pendingParticipatingTable = $sm->get('Qvm\Model\PendingParticipatingTable');
		}
		return $this->pendingParticipatingTable;
	}
	
	/**
	 * Getter du model VoteKindTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\VoteKindTable>
	 */
	public function getVoteKindTable()
	{
		if (!$this->votekindTable) {
			$sm = $this->getServiceLocator();
			$this->votekindTable = $sm->get('Qvm\Model\VoteKindTable');
		}
		return $this->votekindTable;
	}
	
	/**
	 * Getter du model ParticipationTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\ParticipationTable>
	 */
	public function getParticipationTable()
	{
		if (!$this->participationTable) {
			$sm = $this->getServiceLocator();
			$this->participationTable = $sm->get('Qvm\Model\ParticipationTable');
		}
		return $this->participationTable;
	}

}
