<?php

namespace Qvm\Controller;

use Qvm\Model\Comment;
use Qvm\Model\Activity;
use Qvm\Form\CommentForm;
use Qvm\Form\VoteEvenementForm;
use Qvm\Form\ActivityForm;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Qvm\Model\Preference;


/**
 * @author Margot Bernard / Antoine Dumas / Sebastien Gendreau / Cedric Bouygues
 *
 */
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
	
	/**
	 * Action index
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() {
		//R�cup�ration de l'id de l'utilisateur connect�
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table votekindtable on rempli un array
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		//Construction de la vue
		return new ViewModel(array(
    		'activites' => $this->getAllEventsTable()->getActivityByPerson($user_id,5),
			'nbActivites' => count ($this->getAllEventsTable()->getActivityByPerson($user_id,null)),
			'events' => $this->getAllEventsTable()->getEventsByPerson($user_id,5),
			'nbEvents' =>  count ($this->getAllEventsTable()->getEventsByPerson($user_id, null)),
			'form' => $form,
		));
	}
	
	/**
	 * Action utilis�e pour afficher les d�tails d'une activit�
	 * @return \Zend\View\Model\ViewModel
	 */
	public function detailAction() {
		//R�cup�ration de l'id de l'utilisateur connect�
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//R�cup�ration de l'id de l'activit� s�lection�e
		$id = (int) $this->params()->fromRoute('id', 0);
	
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table votekindtable on rempli un array
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		$form->get ( 'voteEvenement' )->setLabel('Mon vote par defaut : ');
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setData ($request->getPost ());
			if ($form->isValid ()) {
				$this->getPreferenceTable()->savePreference($id,(int) $form->get ( 'voteEvenement' )->getValue() ,$user_id );
				return $this->redirect ()->toRoute ( 'activity' );
			}
		}
		
		//R�cup�ration de la cat�gorie de l'activit� s�lection�e
		$activityCategory = $this->getActivityCategoryTable()->getActivityCategory($id);
		
		//R�cup�ration de l'activit� s�lection�e
		$activity = $this->getActivityTable()->getActivity($id);
		
		//Construction de la vue
		return new ViewModel(array(
				'groups' => $this->getGroupTable()->getGroupsByActivity($activity->id_activity),
				'events' => $this->getAllEventsTable()->getEventsByActivityAndPerson($activity->id_activity,$user_id),
				'category' => $this->getCategoryTable()->getCategory($activityCategory->id_category),
				'preference' => $this->getPreferenceTable()->getPreferenceByActivityAndPerson($activity->id_activity,$user_id),
				'form' => $form,
				'activity' => $activity,
		));
	}
	
	/**
	 * Action utilis�e pour afficher le d�tail d'un �v�nement
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function detailEventAction() {
		//R�cup�ration de l'id de l'utilisateur connect�
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//R�cup�ration de l'id de l'event
		$id = (int) $this->params()->fromRoute('id', 0);
		
		// Pagination
		$page = (int) $this->params()->fromRoute('page', 1);
		$persons = $this->getAllEventsTable()->getPersonByEvent($id, null);
		$iteratorAdapter = new Iterator($persons);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(10);
		
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table votekindtable on rempli un array
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		$form->get ( 'voteEvenement' )->setLabel('Participation : ');
		
		//Instantiation du formulaire de commentaire
		$commentForm  = new CommentForm();
		
		//Insertion du commentaire
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
		
		//R�cup�ration donn�es BDD
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
	
	/**
	 * Action utilis�e pour cr�er une activit�
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Qvm\Form\ActivityForm
	 */
	public function createAction() {
		//R�cup�ration de l'id de l'utilisateur connect�
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Instantiation du formulaire d'activit�
		$form = new ActivityForm ();
		$categoryTable = $this->getCategoryTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table category on rempli un array
		foreach ($categoryTable->fetchAll() as $category) {
			$value_options[$category->id_category] = $category->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'categorie' )->setValueOptions($value_options);
		
		//Insertion de l'activit�, de l'admin de l'activit� et de la cat�gorie de l'activit�
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
				$this->getActivityAdminTable()->saveActivityAdmin($id, $user_id);
				return $this->redirect ()->toRoute ( 'activity' );
			}
		}
		
		//Construction de la vue
		return array (
				'form' => $form
		);
	}
	
	/**
	 * Action utilis�e pour lister les activit� de l'utilisateur
	 * @return \Zend\View\Model\ViewModel
	 */
	public function listAction() {
		//R�cup�ration de l'id de l'utilisateur connect�
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Pagination
		$page = (int) $this->params()->fromRoute('page', 1);
		$activites = $this->getAllEventsTable()->getActivityByPerson($user_id,null);
		$iteratorAdapter = new Iterator($activites);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
		
		//Construction de la vue
		return new ViewModel(array(
				'activities' => $paginator,
		));
	}
	
	/**
	 * Action utilis�e pour lister les �v�nements de l'utilisateur connect�
	 * @return \Zend\View\Model\ViewModel
	 */
	public function listEventsAction() {
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table votekindtable on rempli un array
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		//Pagination
		$page = (int) $this->params()->fromRoute('page', 1);
		$events = $this->getAllEventsTable()->getEventsByPerson($user_id,null);
		$iteratorAdapter = new Iterator($events);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
		
		//Construction de la vue
		return new ViewModel(array(
				'events' => $paginator,
				'form' => $form,
				
		));
	}
	
	/**
	 * Action utilis�e pour lister les �v�nements en attente de vote de l'utilisateur connect�
	 * @return \Zend\View\Model\ViewModel
	 */
	public function listPendingParticipatingAction() {
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		
		//Instantiation du formulaire de vote
		$form  = new VoteEvenementForm();
		$votekindTable = $this->getVoteKindTable();
		$value_options = array();
		
		//Pour chaque entr�e pr�sente dans la table votekindtable on rempli un array
		foreach ($votekindTable->fetchAll() as $votekind) {
			$value_options[$votekind->id_votekind] = $votekind->label;
		}
		
		//On rempli l'�l�ment select du formulaire de vote avec l'array
		$form->get ( 'voteEvenement' )->setValueOptions($value_options);
		
		//Pagination
		$page = (int) $this->params()->fromRoute('page', 1);
		$events = $this->getPendingParticipatingTable()->getPendingParticipatingByPerson($user_id, null);
		$iteratorAdapter = new Iterator($events);
		$paginator = new Paginator($iteratorAdapter);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(15);
	
		//Construction de la vue
		return new ViewModel(array(
				'events' => $paginator,
				'form' => $form,
		));
	}

	/**
	 * Getter du model ActivityTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\ActivityTable>
	 */
	public function getActivityTable()
	{
		if (!$this->activityTable) {
			$sm = $this->getServiceLocator();
			$this->activityTable = $sm->get('Qvm\Model\ActivityTable');
		}
		return $this->activityTable;
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
	 * Getter du model CategoryTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\CategoryTable>
	 */
	public function getCategoryTable()
	{
		if (!$this->categoryTable) {
			$sm = $this->getServiceLocator();
			$this->categoryTable = $sm->get('Qvm\Model\CategoryTable');
		}
		return $this->categoryTable;
	}
	
	/**
	 * Getter du model AllEventsTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\AllEventsTable>
	 */
	public function getAllEventsTable()
	{
		if (!$this->allEventsTable) {
			$sm = $this->getServiceLocator();
			$this->allEventsTable = $sm->get('Qvm\Model\AllEventsTable');
		}
		return $this->allEventsTable;
	}
	
	/**
	 * Getter du model ActivityCategoryTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\ActivityCategoryTable>
	 */
	public function getActivityCategoryTable()
	{
		if (!$this->activityCategoryTable) {
			$sm = $this->getServiceLocator();
			$this->activityCategoryTable = $sm->get('Qvm\Model\ActivityCategoryTable');
		}
		return $this->activityCategoryTable;
	}
	/**
	 * Getter du model ActivityAdminTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\ActivityAdminTable>
	 */
	public function getActivityAdminTable()
	{
		if (!$this->activityAdminTable) {
			$sm = $this->getServiceLocator();
			$this->activityAdminTable = $sm->get('Qvm\Model\ActivityAdminTable');
		}
		return $this->activityAdminTable;
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
	 * Getter du model PreferenceTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\PreferenceTable>
	 */
	public function getPreferenceTable()
	{
		if (!$this->preferenceTable) {
			$sm = $this->getServiceLocator();
			$this->preferenceTable = $sm->get('Qvm\Model\PreferenceTable');
		}
		return $this->preferenceTable;
	}
	
	/**
	 * Getter du model CommentTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\CommentTable>
	 */
	public function getCommentTable()
	{
		if (!$this->commentTable) {
			$sm = $this->getServiceLocator();
			$this->commentTable = $sm->get('Qvm\Model\CommentTable');
		}
		return $this->commentTable;
	}
	
	/**
	 * Getter du model UserTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\UserTable>
	 */
	public function getUserTable()
	{
		if (!$this->userTable) {
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Qvm\Model\UserTable');
		}
		return $this->userTable;
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

}