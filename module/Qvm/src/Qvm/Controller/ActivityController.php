<?php

namespace Qvm\Controller;

use Qvm\Model\GroupTable;
use Qvm\Form\VoteEvenementForm;
use Qvm\Model\AllEvents;
use Qvm\Model\AllEventsTable;
use Qvm\Model\VoteKindTable;
use Qvm\Model\CategoryTable;
use Qvm\Model\ActivityCategoryTable;
use Qvm\Model\Activity;
use Qvm\Model\ActivityCategory;
use Qvm\Form\ActivityForm;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class ActivityController extends AbstractActionController {
	protected $activityTable;
	protected $categoryTable;
	protected $activityCategoryTable;
	protected $allEventsTable;
	protected $votekindTable;
	protected $groupTable;
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
    		'activites' => $this->getActivityTable()->fetchLimit(),
			'nbActivites' => count ($this->getActivityTable()->fetchAll()),
			'events' => $this->getAllEventsTable()->fetchLimit(),
			'nbEvents' =>  count ($this->getAllEventsTable()->fetchAll()),
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
		
		$id = (int) $this->params()->fromRoute('id', 0);
		$activity = $this->getActivityTable()->getActivity($id);
		
		return new ViewModel(array(
				'groups' => $this->getGroupTable()->getGroupsByActivity($activity->id_activity),
				'form' => $form,
				'activity' => $activity,
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
	
/*	public function listAction() {
		return new ViewModel(array(
    		'activites' => $this->getActivityTable()->fetchLimit(),
			'nbActivites' => count ($this->getActivityTable()->fetchAll()),
		));
	}*/
	
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
	

}