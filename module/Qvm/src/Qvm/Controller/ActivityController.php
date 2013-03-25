<?php

namespace Qvm\Controller;

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
	protected $value_options;
	
	public function indexAction() {
		return;
	}
	
	public function getActivityTable()
	{
		if (!$this->activityTable) {
			$sm = $this->getServiceLocator();
			$this->activityTable = $sm->get('Qvm\Model\ActivityTable');
		}
		return $this->activityTable;
	}
	
	public function getCategoryTable()
	{
		if (!$this->categoryTable) {
			$sm = $this->getServiceLocator();
			$this->categoryTable = $sm->get('Qvm\Model\CategoryTable');
		}
		return $this->categoryTable;
	}
	
	public function getActivityCategoryTable()
	{
		if (!$this->activityCategoryTable) {
			$sm = $this->getServiceLocator();
			$this->activityCategoryTable = $sm->get('Qvm\Model\ActivityCategoryTable');
		}
		return $this->activityCategoryTable;
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
}