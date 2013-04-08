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
	
	/**
	 * Récupération des événements d'une activité et d'un utilisateur
	 * @param unknown $id_activity
	 * @param unknown $user_id
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getEventsByActivityAndPerson($id_activity,$user_id)
	{
		$select = new Select;
		$select->columns(array('id_event', 'id_activity', 'title','location_name','location_url','user_id','firstname','surname','date','vote'))->from('allevents')
		->where(array('allevents.id_activity' => $id_activity))
		->where(array('allevents.user_id' => $user_id))
		->limit(5)
		->order('allevents.date');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		return $resultSet;

	}
	
	/**
	 * Récupération des événéments d'un utilisateur
	 * @param unknown $user_id
	 * @param unknown $limit
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getEventsByPerson($user_id, $limit)
	{
		$select = new Select;
		$select->columns(array('id_event','title','date','vote'))->from('allevents')
		->where(array('allevents.user_id' => $user_id))
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
	
	/**
	 * Récupération des membres participants à un événement
	 * @param unknown $id_event
	 * @param unknown $limit
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getPersonByEvent($id_event, $limit)
	{
		$select = new Select;
		$select->columns(array('user_id','firstname','surname','vote'))->from('allevents')
		->where(array('allevents.id_event' => $id_event))
		->limit($limit)
		->order('allevents.user_id');
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		$resultSet->next();
		return $resultSet;
	
	}
	
	/**
	 * Récupération des activités d'un utilisateur
	 * @param unknown $user_id
	 * @param unknown $limit
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getActivityByPerson($user_id, $limit)
	{
		$select = new Select;
		$select->quantifier('DISTINCT')
		->columns(array('id_activity','title','location_name','location_url'))->from('allevents')
		->where(array('allevents.user_id' => $user_id))
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