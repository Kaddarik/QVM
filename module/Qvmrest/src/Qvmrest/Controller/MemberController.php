<?php

namespace Qvmrest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class MemberController extends AbstractRestfulController
{
	protected $userTable;
	
	public function getUserTable()
	{
		if(!$this->userTable)
		{
			$s = $this->getServiceLocator();
			$this->userTable = $s->get('Qvm\Model\UserTable');
		}
		return $this->userTable;
	}
	
	public function getList()
	{
		$results = $this->getUserTable();
		$data = array();
		foreach ($results as $result)
		{
			$data[] = $result;
		}
		return $this->getResponse()->setContent(json_encode($data));
	}
	
	public function get($id)
	{
		$results = $this->getUserTable()->getMembersByGroup($id);
		$data = array();
		foreach ($results as $result)
		{
			$data[] = $result;
		}
		return $this->getResponse()->setContent(json_encode($data));
	}
	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::create()
	 */public function create($data) {
		// TODO Auto-generated method stub
		}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::update()
	 */public function update($id, $data) {
		// TODO Auto-generated method stub
		}

	/* (non-PHPdoc)
	 * @see \Zend\Mvc\Controller\AbstractRestfulController::delete()
	 */public function delete($id) {
		// TODO Auto-generated method stub
		}

}