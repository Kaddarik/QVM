<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class UpcomingParticipatingTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('date')->limit(5);
		});
		return $resultSet;
	}

	public function getUpcomingParticipating($id)
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
	 * Récupération des événements en attente de vote pour un utilisateur
	 * @param unknown $user_id
	 * @param unknown $limit
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getUpcomingParticipatingByPerson($user_id, $limit)
	{
		$select = new Select;
		$select->columns(array('id_event','title','date','vote'))->from('upcomingparticipating')
		->where(array('upcomingparticipating.user_id' => $user_id))
		->limit($limit)
		->order('upcomingparticipating.date');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		$resultSet->next();
		return $resultSet;
	
	}
}