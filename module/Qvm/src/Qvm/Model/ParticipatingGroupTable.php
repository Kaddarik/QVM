<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;

class ParticipatingGroupTable
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
	
	public function fetchByActivity($idActivity)
	{	
		$where = 'id_activity=' + $idActivity;
		$resultSet = $this->tableGateway->select($where);
		return $resultSet;
	}
}