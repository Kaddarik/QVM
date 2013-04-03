<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class PreferenceTable
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
	
	public function getPreferenceByActivityAndPerson($id_activity, $id_person)
	{
		$select = new Select;
		$select->from('preference');
		$select->where->equalTo('preference.id_activity', $id_activity);
		$select->where->equalTo('preference.id_activity', $id_activity);

		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();
		return $row;
	}
		

}