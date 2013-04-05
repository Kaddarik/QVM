<?php
namespace Qvmrest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

class EventController extends AbstractRestfulController
{
	protected $UpcomingParticipatingTable;
	
	public function getUpcomingParticipatingTable()
	{
		if(!$this->UpcomingParticipatingTable)
		{
			$s = $this->getServiceLocator();
			$this->UpcomingParticipatingTable = $s->get('Qvm\Model\UpcomingParticipatingTable');
		}
		return $this->UpcomingParticipatingTable;
	}
	
	public function getList()
	{
		$idPers = 1;
		$results = $this->getUpcomingParticipatingTable()->getUpcomingParticipatingByPerson(1, null);
		$data = array();
		foreach ($results as $result)
		{
			$data[] = $result;
		}
		return $this->getResponse()->setContent(json_encode($data));
	}
	
	public function get($id)
	{
		$results = $this->getUpcomingParticipatingTable()->getUpcomingParticipating($id);
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