<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\AbstractTableGateway;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
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
	
	public function fetchLimit()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->where->equalTo('is_private', 0);
			$select->order('label')->limit(5);
		});
		return $resultSet;
	}
	
	public function getMembersByGroup($idGroupe){
		
		
		$select = new Select('person');
		$select->columns(array('firstname', 'surname', 'mail', 'phonenumber', 'is_sysadmin'))
			->join('groupmember', 'person.id_person = groupmember.id_person', array())
			->join('group', 'groupmember.id_group = group.id_group', array())
			->where(array('group.id_group' => $idGroupe));
		
		/*	$select = new Select();
			$select->columns(array('firstname', 'surname', 'mail', 'phonenumber', 'is_sysadmin'))->from('person')
			->join('groupmember', 'groupmember.id_person = person.id_person', array())
			->join('group', 'group.id_group = group.id_group', array());
		
			$where = new Where();
			$where->equalTo('id_group', $idGroupe) ;
			$select->where($where);*/
		
			echo $select->getSqlString(new \Zend\Db\Adapter\Platform\Mysql());
			
			
			//return $this->tableGateway->selectWith($select);
		
		
		
		
		
		
		//return $this->tableGateway->selectWith($select);
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