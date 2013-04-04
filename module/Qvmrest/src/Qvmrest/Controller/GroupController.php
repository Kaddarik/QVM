<?php
namespace Qvmrest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class GroupController extends AbstractRestfulController
{
	protected $GroupeTable;
	
	public function getGroupTable()
	{
		if(!$this->GroupeTable)
		{
			$s = $this->getServiceLocator();
			$this->GroupeTable = $s->get('Qvm\Model\GroupTable');
		}
		return $this->GroupeTable;
	}
	
	public function getList()
	{
		$idPers = 1;
		$results = $this->getGroupTable()->getGroupByPerson(1, null);
		$data = array();
		foreach ($results as $result)
		{
			$data[] = $result;
		}
		return $this->getResponse()->setContent(json_encode($data));
	}
	
	public function get($id)
	{
		$results = $this->getGroupTable()->getGroup($id);
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