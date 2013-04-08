<?php
namespace Qvm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Qvm\Form\UserForm;

class UserController extends AbstractActionController
{
	protected $userTable;
	
	public function indexAction()
	{
		return;
	}
	
	public function settingsAction(){
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$user = $this->getUserTable()->getUser($user_id);
			
		$form  = new UserForm();
		$form->bind($user);
	
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());
	
			if ($form->isValid()) {
				$this->getUserTable()->saveUser($form->getData());
	
				return $this->redirect()->toRoute('indexqvm');
			}
		}
	
		return array(
				'id' => $user_id,
				'form' => $form,
		);
	}
	
	public function getUserTable()
	{
		if (!$this->userTable) {
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Qvm\Model\UserTable');
		}
		return $this->userTable;
	}
}