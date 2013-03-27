<?php
namespace Qvm\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Platform\Mysql;

class GroupTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			//liste tous les groupes publics
			$select->where->equalTo('is_private', 0);
		});
		return $resultSet;
	}
	
	public function fetchLimit($nbLimit)
	{
		$select = new Select;
		$select->from('group');
		$select->where->equalTo('is_private', 0);
		$select->order('label')->limit($nbLimit);
		
		return $this->tableGateway->selectWith($select);
	}
	
	public function getMembersByGroup($idGroupe){
		$select = new Select;
		$select->columns(array('firstname', 'surname', 'mail', 'phonenumber', 'is_sysadmin'))->from('person')
			->join('groupmember', 'person.id_person = groupmember.id_person', array())
			->join('group', 'groupmember.id_group = group.id_group', array())
			->where(array('group.id_group' => $idGroupe))
			->order('is_sysadmin DESC');
		
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		
		return $resultSet;
	}
	
	public function getActivitesByGroup($idGroupe){
		$select = new Select;
		$select->columns(array('id_activity', 'title'))->from('activity')
			->join('participatinggroup', 'activity.id_activity = participatinggroup.id_activity', array())
			->join('group', 'group.id_group = participatinggroup.id_group', array())
			->where(array('group.id_group' => $idGroupe));
		//echo $select->getSqlString(new \Zend\Db\Adapter\Platform\Mysql());
		
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		
		return $resultSet;
	}
	
	public function getGroup($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_group' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
}