<?php

namespace Qvm\Controller;

use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Qvm\Model\PreferenceTable;
use Qvm\Model\GroupTable;
use Qvm\Form\VoteEvenementForm;
use Qvm\Model\AllEvents;
use Qvm\Model\AllEventsTable;
use Qvm\Model\PersonTable;
use Qvm\Model\VoteKindTable;
use Qvm\Model\CommentTable;
use Qvm\Model\CategoryTable;
use Qvm\Model\ActivityCategoryTable;
use Qvm\Model\Activity;
use Qvm\Model\ActivityCategory;
use Qvm\Form\ActivityForm;
use Zend\I18n\View\Helper\DateFormat;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class ActivityController extends AbstractActionController {
	protected $activityTable;
	protected $categoryTable;
	protected $activityCategoryTable;
	protected $allEventsTable;
	protected $votekindTable;
	protected $groupTable;
	protected $preferenceTable;
	protected $commentTable;
	protected $personTable;
	protected $pendingParticipatingTable;
	protected $value_options;
	
	
	public function indexAction() {
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		return new ViewModel(array(
    		'activites' => $this->getAllEventsTable()->getActivityByPerson(1,5),
			'nbActivites' => count ($this->getAllEventsTable()->getActivityByPerson(1,null)),
			'events' => $this->getAllEventsTable()->getEventsByPerson(1,5),
			'nbEvents' =>  count ($this->getAllEventsTable()->getEventsByPerson(1, null)),
			'form' => $form,
		));
	}
	
	public function detailAction() {
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
				'events' => $this->getAllEventsTable()->getEventsByActivityAndPerson($activity->id_activity,1),
				'category' => $this->getCategoryTable()->getCategory($activityCategory->id_category),
				'preference' => $this->getPreferenceTable()->getPreferenceByActivityAndPerson($activity->id_activity, 1),
				'form' => $form,
				'activity' => $activity,
		));
	}
	
	public function detailEventAction() {
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
		
		//Recuperation donnees BDD
		$event = $this->getAllEventsTable()->getEvent($id);
		$activity = $this->getActivityTable()->getActivity($event->id_activity);
		$comments = $this->getCommentTable()->getCommentByEvent($id);
		
		//Construction de la vue
		return new ViewModel(array(
				'comments' => $comments,
				'form' => $form,
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
				return $this->redirect ()->toRoute ( 'activity' );
			}
		}
		return array (
				'form' => $form
		);
	}
	
	public function listAction() {
		$page = (int) $this->params()->fromRoute('page', 1);
		$activites = $this->getAllEventsTable()->getActivityByPerson(1,null);
		$iteratorAdapter = new Iterator($activites);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
		return new ViewModel(array(
				'activities' => $paginator,
		));
	}
	
	public function listEventsAction() {
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		$page = (int) $this->params()->fromRoute('page', 1);
		$events = $this->getAllEventsTable()->getEventsByPerson(1,null);
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
		$events = $this->getPendingParticipatingTable()->getPendingParticipatingByPerson(1, null);
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
	
	public function getPersonTable()
	{
		if (!$this->personTable) {
			$sm = $this->getServiceLocator();
			$this->personTable = $sm->get('Qvm\Model\PersonTable');
		}
		return $this->personTable;
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