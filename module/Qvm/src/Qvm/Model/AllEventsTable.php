<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class AllEventsTable
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
	
	public function getEvent($id_event)
	{
		$id_event  = (int) $id_event;
		$rowset = $this->tableGateway->select(array('id_event' => $id_event));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id_event");
		}
		return $row;
	}
	
	public function getEventsByActivityAndPerson($id_activity,$id_person)
	{
		$select = new Select;
		$select->columns(array('id_event', 'id_activity', 'title','location_name','location_url','id_person','firstname','surname','date','vote'))->from('allevents')
		->where(array('allevents.id_activity' => $id_activity))
		->where(array('allevents.id_person' => $id_person))
		->limit(5)
		->order('allevents.date');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		return $resultSet;

	}
	
	public function getEventsByPerson($id_person, $limit)
	{
		$select = new Select;
		$select->columns(array('id_event','title','date','vote'))->from('allevents')
		->where(array('allevents.id_person' => $id_person))
		->limit($limit)
		->order('allevents.date');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		$resultSet->next();
		return $resultSet;
	
	}
	

	public function getPersonByEvent($id_event, $limit)
	{
		$select = new Select;
		$select->columns(array('id_person','firstname','surname','vote'))->from('allevents')
		->where(array('allevents.id_event' => $id_event))
		->limit($limit)
		->order('allevents.id_person');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		$resultSet->next();
		return $resultSet;
	
	}
	
	public function getActivityByPerson($id_person, $limit)
	{
		$select = new Select;
		$select->columns(array('id_activity','title','location_name','location_url'))->from('allevents')
		->where(array('allevents.id_person' => $id_person))
		->limit($limit)
		->order('allevents.title');
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