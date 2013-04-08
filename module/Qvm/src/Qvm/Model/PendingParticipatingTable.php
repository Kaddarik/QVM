<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class PendingParticipatingTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('date');
		});
		return $resultSet;
	}
	
	public function fetchLimit()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('date')->limit(5);
		});
		return $resultSet;
	}
	
	
	public function getPendingParticipating($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_event' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getPendingParticipatingByPerson($user_id, $limit)
	{
		$select = new Select;
		$select->columns(array('id_event','title','date','vote'))->from('pendingparticipating')
		->where(array('pendingparticipating.user_id' => $user_id))
		->limit($limit)
		->order('pendingparticipating.date');
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