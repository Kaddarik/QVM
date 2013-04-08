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

/**
 * @author Margot Bernard / Antoine Dumas / Sebastien Gendreau / Cedric Bouygues
 *
 */
class GroupController extends AbstractActionController
{
	protected $groupTable;
	protected $activityTable;
	protected $userTable;
	protected $groupMemberTable;

	/**
	 * Action index
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
    public function indexAction()
    {    	
    	//Recuperation de l'id de l'utilisateur connecte
    	$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	
    	//Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$groups = $this->getGroupTable()->getGroupByPerson($user_id, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(10);
    	
    	//Instantiation du formulaire de vote
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
    	
    	//Construction de la vue
    	return new ViewModel(array(
    		'groups' => $paginator,
			'form' => $form ,   		
    	));
    }
    
    /**
     * Action utilisée pour afficher les détails du groupe
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:number \Zend\Db\ResultSet\ResultSet mixed
     */
    public function detailsAction(){
    	$maxMembres = 10;
    	$maxActivites = 10;
    	
    	//Renvoi l'id du groupe
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}

    	//Construction de la vue
    	 return array(
            'id' => $idGroupe,
            'group' => $this->getGroupTable()->getGroup($idGroupe),
    	 	'membres' => $this->getUserTable()->getMembersByGroup($idGroupe),
    	 	'maxMembres' => $maxMembres,
    	 	'activites' => $this->getActivityTable()->getActivitesByGroup($idGroupe),
    	 	'maxActivites' => $maxActivites,
        );
    }
    
    /**
     * Action utilisée pour lister les membres du groupe
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Zend\Paginator\Paginator mixed
     */
    public function listeMembresAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}
    	
    	// Pagination 
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$membres = $this->getUserTable()->getMembersByGroup($idGroupe);
    	$iteratorAdapter = new Iterator($membres);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	//Construction de la vue
    	return array(
    		'group' => $this->getGroupTable()->getGroup($idGroupe),
    		'membres' => $paginator
    	);
    }
    
    /**
     * Action utilisée pour lister les activités du groupe
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Zend\Paginator\Paginator mixed
     */
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
 	
    	//Construction de la vue
    	return array(
    			'group' => $this->getGroupTable()->getGroup($idGroupe),
    			'activites' => $paginator
    	);
    }
    
    /**
     * Action qui permet l'ajout d'un groupe
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Qvm\Form\GroupForm
     */
    public function createAction() {
    	//Recuperation de l'id de l'utilisateur connecte
    	$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	
    	//Instantiation du formulaire de vote
    	$form = new GroupForm ();
    	
    	//Insertion du group et de l'admin de du groupe
    	$request = $this->getRequest ();
    	if ($request->isPost ()) {
    		$group = new Group();
    		$form->setInputFilter ( $group->getInputFilter () );
    		$form->setData ($request->getPost ());
    		if ($form->isValid ()) {
    			$group->exchangeArray($form->getData());
    			$this->getGroupTable()->saveGroup($group);
    			$id = $this->getGroupTable()->getLastGroup();
    			$this->getGroupMemberTable()->saveGroupAdmin($id, $user_id);
    			
    			return $this->redirect ()->toRoute ( 'group' );
    		}
    	}
    	
    	//Construction de la vue
    	return array (
    			'form' => $form
    	);
    }
    
    /**
     * Action utilisée pour rejoindre un groupe
     * @return multitype:number \Zend\Paginator\Paginator NULL
     */
    public function rejoindreAction(){
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$groups = $this->getGroupTable()->getGroupByPerson($user_id, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	
    	//Construction de la vue
    	return array(
    		'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id),	
    		'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id)),
    		'groups' => $paginator
    	);
    }
    
    /**
     * Action utilisée pour valider une invitation privée
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
     */
    public function majGroupsPrivateInvitValidAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}
    	
    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$groups = $this->getGroupTable()->getGroupByPerson($user_id, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	$this->getGroupMemberTable()->updateGroupsPrivateInvitValid($user_id, $idGroupe);
    	
    	//Construction de la vue
    	$result = new ViewModel(array(
    		'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id),	
    		'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id)),
    		'groups' => $paginator
    			));
    	
    	//utilisation de la vue rejoindre
    	$result->setTemplate('qvm\group\rejoindre.phtml');
    	
    	return $result;
    }
    
    /**
     * Action utilisée pour refuser une invitation privée
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
     */
    public function majGroupsPrivateInvitRefusAction(){
    	$idGroupe = (int) $this->params()->fromRoute('id', 0);
    	if (!$idGroupe) {
    		return $this->redirect()->toRoute('group', array(
    				'action' => 'index'
    		));
    	}

    	// Pagination
    	$page = (int) $this->params()->fromRoute('page', 1);
    	$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
    	$groups = $this->getGroupTable()->getGroupByPerson($user_id, null);
    	$iteratorAdapter = new Iterator($groups);
    	$paginator = new Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(15);
    	$this->getGroupMemberTable()->updateGroupsPrivateInvitRefus($user_id, $idGroupe);
    	 
    	//Construction de la vue
    	$result = new ViewModel(array(
    			'groupsPrivate' => $this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id),
    			'nbGroupsPrivate' => count($this->getGroupTable()->getGroupsPrivateInvitByPerson($user_id)),
    			'groups' => $paginator
    	));
    	//Utilisation de la vue rejoindre
    	$result->setTemplate('qvm\group\rejoindre.phtml');
    	 
    	return $result;
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
	 * Getter du model GroupMemberTable
	 * @return Ambigous <object, multitype:, \Qvm\Model\GroupMemberTable>
	 */
	public function getGroupMemberTable()
	{
		if (!$this->groupMemberTable) {
			$sm = $this->getServiceLocator();
			$this->groupMemberTable = $sm->get('Qvm\Model\GroupMemberTable');
		}
		return $this->groupMemberTable;
	}
	
}