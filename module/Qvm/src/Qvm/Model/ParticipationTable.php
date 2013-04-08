<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ParticipationTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getParticipation($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_event' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	/**
	 * Mise à jour de la participation d'un utilisateur à un événement
	 * @param unknown $id_event
	 * @param unknown $vote
	 * @param unknown $user_id
	 */
	public function updateParticipation($id_event, $vote, $user_id)
	{
		$data = array(
				'id_event'  => $id_event,
				'user_id' =>  $user_id,
				'vote' => $vote,
		);

		$this->tableGateway->update($data, array('id_event' => $id_event, 'user_id' => $user_id));
	}
}