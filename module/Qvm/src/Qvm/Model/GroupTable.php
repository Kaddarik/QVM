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
	
	public function getGroupsPrivate()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			//liste tous les groupes privés
			$select->where->equalTo('is_private', 1);
		});
		return $resultSet;
	}
	
	public function getGroupsByActivity($idActivity){
		$select = new Select;
		$select->columns(array('id_group', 'label', 'is_private'))->from('group')
		->join('participatinggroup', 'group.id_group = participatinggroup.id_group', array())
		->join('activity', 'participatinggroup.id_activity = activity.id_activity', array())
		->where(array('activity.id_activity' => $idActivity));
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
	
	public function saveGroup(Group $group)
	{
		$data = array(
			'label'  => $group->label,
		);
	
		$id_group = (int)$group->id_group;
		if ($id_group == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getGroup($id_group)) {
				$this->tableGateway->update($data, array('id_group' => $id_group));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
}