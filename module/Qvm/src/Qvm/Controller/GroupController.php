<?php 
namespace Qvm\Controller;

use Zend\View\Model\ViewModel;
use Zend\Validator\File\Count;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\Mvc\Controller\AbstractActionController;
use Qvm\Form\RechercheGroupeForm;
use Qvm\Form\GroupForm;
use Qvm\Model\Group;

class GroupController extends AbstractActionController
{
	protected $groupTable;
	protected $activityTable;
	protected $personTable;
	protected $groupMemberTable;

    public function indexAction()
    {    	
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$groups = $this->getGroupTable()->getGroupByPerson(1,null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(10);
    	
    	$form  = new RechercheGroupeForm();
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$group = new Group();
    		$form->setInputFilter($group->getInputFilter());
    		$form->setData($request->getPost());
    		 
    		if ($form->isValid()) {
    			//SAVE TO DATABASE...
    		}
    	}
    	return new ViewModel(array(
    		'groups' => $paginator,
			'form' => $form ,   		
    	));
    }
    
    
    public function detailsAction(){
    	//Renvoi l'id du groupe
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}

    	 return array(
            'id' => $idGroupe,
            'group' => $this->getGroupTable()->getGroup($idGroupe),
    	 	'membres' => $this->getPersonTable()->getMembersByGroup($idGroupe),
    	 	'activites' => $this->getActivityTable()->getActivitesByGroup($idGroupe),
        );
    }
    
    public function listeMembresAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}
    	
    	// Pagination 
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$membres = $this->getPersonTable()->getMembersByGroup($idGroupe);
    	$iteratorAdapter = new Iterator($membres);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	return array(
    		'group' => $this->getGroupTable()->getGroup($idGroupe),
    		'membres' => $paginator
    	);
    }
    
    public function listeActivitesAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}
    	
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$activites = $this->getActivityTable()->getActivitesByGroup($idGroupe);
    	$iteratorAdapter = new Iterator($activites);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
 	
    	return array(
    			'group' => $this->getGroupTable()->getGroup($idGroupe),
    			'activites' => $paginator
    	);
    }
    
    public function createAction() {
    	$form = new GroupForm ();
    	$request = $this->getRequest ();
    	if ($request->isPost ()) {
    		$group = new Group();
    		$form->setInputFilter ( $group->getInputFilter () );
    		$form->setData ($request->getPost ());
    		if ($form->isValid ()) {
    			$group->exchangeArray($form->getData());
    			$this->getGroupTable()->saveGroup($group);
    			
    			return $this->redirect ()->toRoute ( 'group' );
    		}
    	}
    	return array (
    			'form' => $form
    	);
    }
    
    public function rejoindreAction(){
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$groups = $this->getGroupTable()->getGroupByPerson(1, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	return array(
    		'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson(1),	
    		'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson(1)),
    		'groups' => $paginator
    	);
    }
    
    public function majGroupsPrivateInvitValidAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}
    	
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$groups = $this->getGroupTable()->getGroupByPerson(1, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	$this->getGroupMemberTable()->updateGroupsPrivateInvitValid(1, $idGroupe);
    	
    	$result = new ViewModel(array(
    		'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson(1),	
    		'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson(1)),
    		'groups' => $paginator
    			));
    	$result->setTemplate('qvm\group\rejoindre.phtml');
    	
    	return $result;
    }
    
    public function majGroupsPrivateInvitRefusAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}

    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$groups = $this->getGroupTable()->getGroupByPerson(1, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	$this->getGroupMemberTable()->updateGroupsPrivateInvitRefus(1, $idGroupe);
    	 
    	$result = new ViewModel(array(
    			'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson(1),
    			'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson(1)),
    			'groups' => $paginator
    	));
    	$result->setTemplate('qvm\group\rejoindre.phtml');
    	 
    	return $result;
    }
    
	public function getGroupTable()
	{
		if (!$this->groupTable) {
			$sm = $this->getServiceLocator();
			$this->groupTable = $sm->get('Qvm\Model\GroupTable');
		}
		return $this->groupTable;
	}
	
	public function getActivityTable()
	{
		if (!$this->activityTable) {
			$sm = $this->getServiceLocator();
			$this->activityTable = $sm->get('Qvm\Model\ActivityTable');
		}
		return $this->activityTable;
	}
	
	public function getPersonTable()
	{
		if (!$this->personTable) {
			$sm = $this->getServiceLocator();
			$this->personTable = $sm->get('Qvm\Model\PersonTable');
		}
		return $this->personTable;
	}
	
	public function getGroupMemberTable()
	{
		if (!$this->groupMemberTable) {
			$sm = $this->getServiceLocator();
			$this->groupMemberTable = $sm->get('Qvm\Model\GroupMemberTable');
		}
		return $this->groupMemberTable;
	}
	
}