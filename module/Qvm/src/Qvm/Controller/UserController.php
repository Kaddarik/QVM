<?php
namespace Qvm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Qvm\Form\UserForm;

/**
 * @author Margot Bernard / Antoine Dumas / Sebastien Gendreau / Cedric Bouygues
 *
 */
class UserController extends AbstractActionController
{
	protected $userTable;
	
	public function indexAction()
	{
		return;
	}
	/**
	 * Action utilisée pour modifier le compte de l'utilisateur
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|multitype:\Qvm\Form\UserForm unknown
	 */
	public function settingsAction(){
		//Recuperation de l'id de l'utilisateur connecte
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();

		//Instantiation du formulaire de l'utilisateur
		$form  = new UserForm();
		
		//On remplit le formulaire avec les informations récupérées
		$user = $this->getUserTable()->getUser($user_id);
		$form->bind($user);
	
		//Insertion de l'utilisateur modifié
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());
	
			if ($form->isValid()) {
				$this->getUserTable()->saveUser($form->getData());
	
				return $this->redirect()->toRoute('indexqvm');
			}
		}
	
		//Construction de la vue
		return array(
				'id' => $user_id,
				'form' => $form,
		);
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
}