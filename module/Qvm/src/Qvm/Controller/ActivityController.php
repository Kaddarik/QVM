<?php

namespace Qvm\Controller;

use Qvm\Model\Comment;

use Qvm\Form\CommentForm;

use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Qvm\Form\VoteEvenementForm;
use Qvm\Model\Activity;
use Qvm\Form\ActivityForm;


class ActivityController extends AbstractActionController {
	protected $activityTable;
	protected $categoryTable;
	protected $activityCategoryTable;
	protected $activityAdminTable;
	protected $allEventsTable;
	protected $votekindTable;
	protected $groupTable;
	protected $preferenceTable;
	protected $commentTable;
	protected $userTable;
	protected $pendingParticipatingTable;
	protected $value_options;
	
	
	public function indexAction() {
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		return new ViewModel(array(
    		'activites' => $this->getAllEventsTable()->getActivityByPerson($user_id,5),
			'nbActivites' => count ($this->getAllEventsTable()->getActivityByPerson($user_id,null)),
			'events' => $this->getAllEventsTable()->getEventsByPerson($user_id,5),
			'nbEvents' =>  count ($this->getAllEventsTable()->getEventsByPerson($user_id, null)),
			'form' => $form,
		));
	}
	
	public function detailAction() {
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		$form->get ( 'voteEvenement' )->setLabel('Mon vote par defaut : ');
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$activityCategory = $this->getActivityCategoryTable()->getActivityCategory($id);
		$activity = $this->getActivityTable()->getActivity($id);

		
		return new ViewModel(array(
				'groups' => $this->getGroupTable()->getGroupsByActivity($activity->id_activity),
				'events' => $this->getAllEventsTable()->getEventsByActivityAndPerson($activity->id_activity,$user_id),
				'category' => $this->getCategoryTable()->getCategory($activityCategory->id_category),
				'preference' => $this->getPreferenceTable()->getPreferenceByActivityAndPerson($activity->id_activity,$user_id),
				'form' => $form,
				'activity' => $activity,
		));
	}
	
	public function detailEventAction() {
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Recuperation de l'id de l'event
		$id = (int) $this->params()->fromRoute('id', 0);
		
		// Pagination
		$page = (int) $this->params()->fromRoute('page', 1);
		$persons = $this->getAllEventsTable()->getPersonByEvent($id, null);
		$iteratorAdapter = new Iterator($persons);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(10);
		
		// Liste deroulante
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		$form->get ( 'voteEvenement' )->setLabel('Participation : ');
		
		// Form commentaire
		$commentForm  = new CommentForm();
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$comment = new Comment();
			$commentForm->setInputFilter ( $comment->getInputFilter() );
			$commentForm->setData ($request->getPost ());
			if ($commentForm->isValid ()) {
				$comment->exchangeArray($commentForm->getData());
				$comment->id_event = $id;
				$comment->user_id = $user_id;
				$this->getCommentTable()->saveComment($comment);
				return $this->redirect ()->toRoute ( 'activity' );
			}
		}
		//Recuperation donnees BDD
		$event = $this->getAllEventsTable()->getEvent($id);
		$activity = $this->getActivityTable()->getActivity($event->id_activity);
		$comments = $this->getCommentTable()->getCommentByEvent($id);
		
	
		//Construction de la vue
		return new ViewModel(array(
				'comments' => $comments,
				'form' => $form,
				'formComment' => $commentForm,
				'activity' => $activity,
				'event' => $event,
				'persons' => $paginator,
		));
	}
	
	public function createAction() {
		$form = new ActivityForm ();
		$categoryTable = $this->getCategoryTable();
		$value_options = array();
		foreach ($categoryTable->fetchAll() as $category) {
			$value_options[$category->id_category] = $category->label;
		}
		$form->get ( 'categorie' )->setValueOptions($value_options);
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$activity = new Activity();
			$form->setInputFilter ( $activity->getInputFilter () );
			$form->setData ($request->getPost ());
			if ($form->isValid ()) {
				$activity->exchangeArray($form->getData());
				$this->getActivityTable()->saveActivity($activity);
				$id = $this->getActivityTable()->getLastActivity();
				$this->getActivityCategoryTable()->saveActivityCategory($id,$form->get ( 'categorie' )->getValue());
				$this->getActivityAdminTable()->saveActivityAdmin($id, $this->zfcUserAuthentication()->getIdentity()->getId());
				return $this->redirect ()->toRoute ( 'activity' );
			}
		}
		return array (
				'form' => $form
		);
	}
	
	public function listAction() {
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		$page = (int) $this->params()->fromRoute('page', 1);
		$activites = $this->getAllEventsTable()->getActivityByPerson($user_id,null);
		$iteratorAdapter = new Iterator($activites);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
		return new ViewModel(array(
				'activities' => $paginator,
		));
	}
	
	public function listEventsAction() {
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		$page = (int) $this->params()->fromRoute('page', 1);
		$events = $this->getAllEventsTable()->getEventsByPerson($user_id,null);
		$iteratorAdapter = new Iterator($events);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
		
		return new ViewModel(array(
				'events' => $paginator,
				'form' => $form,
				
		));
	}
	
	public function listPendingParticipatingAction() {
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
	
		$page = (int) $this->params()->fromRoute('page', 1);
		$events = $this->getPendingParticipatingTable()->getPendingParticipatingByPerson($this->zfcUserAuthentication()->getIdentity()->getId(), null);
		$iteratorAdapter = new Iterator($events);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
	
		return new ViewModel(array(
				'events' => $paginator,
				'form' => $form,
		));
	}
	         
	public function getActivityTable()
	{
		if (!$this->activityTable) {
			$sm = $this->getServiceLocator();
			$this->activityTable = $sm->get('Qvm\Model\ActivityTable');
		}
		return $this->activityTable;
	}
	
	public function getVoteKindTable()
	{
		if (!$this->votekindTable) {
			$sm = $this->getServiceLocator();
			$this->votekindTable = $sm->get('Qvm\Model\VoteKindTable');
		}
		return $this->votekindTable;
	}
	
	public function getCategoryTable()
	{
		if (!$this->categoryTable) {
			$sm = $this->getServiceLocator();
			$this->categoryTable = $sm->get('Qvm\Model\CategoryTable');
		}
		return $this->categoryTable;
	}
	
	public function getAllEventsTable()
	{
		if (!$this->allEventsTable) {
			$sm = $this->getServiceLocator();
			$this->allEventsTable = $sm->get('Qvm\Model\AllEventsTable');
		}
		return $this->allEventsTable;
	}
	
	public function getActivityCategoryTable()
	{
		if (!$this->activityCategoryTable) {
			$sm = $this->getServiceLocator();
			$this->activityCategoryTable = $sm->get('Qvm\Model\ActivityCategoryTable');
		}
		return $this->activityCategoryTable;
	}
	
	public function getActivityAdminTable()
	{
		if (!$this->activityAdminTable) {
			$sm = $this->getServiceLocator();
			$this->activityAdminTable = $sm->get('Qvm\Model\ActivityAdminTable');
		}
		return $this->activityAdminTable;
	}
	
	public function getGroupTable()
	{
		if (!$this->groupTable) {
			$sm = $this->getServiceLocator();
			$this->groupTable = $sm->get('Qvm\Model\GroupTable');
		}
		return $this->groupTable;
	}
	

	public function getPreferenceTable()
	{
		if (!$this->preferenceTable) {
			$sm = $this->getServiceLocator();
			$this->preferenceTable = $sm->get('Qvm\Model\PreferenceTable');
		}
		return $this->preferenceTable;
	}
	
	public function getCommentTable()
	{
		if (!$this->commentTable) {
			$sm = $this->getServiceLocator();
			$this->commentTable = $sm->get('Qvm\Model\CommentTable');
		}
		return $this->commentTable;
	}
	
	public function getUserTable()
	{
		if (!$this->userTable) {
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Qvm\Model\UserTable');
		}
		return $this->userTable;
	}
	
	public function getPendingParticipatingTable()
	{
		if (!$this->pendingParticipatingTable) {
			$sm = $this->getServiceLocator();
			$this->pendingParticipatingTable = $sm->get('Qvm\Model\PendingParticipatingTable');
		}
		return $this->pendingParticipatingTable;
	}

}