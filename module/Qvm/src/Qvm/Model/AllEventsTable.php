<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class AllEventsTable
{
	protected $tableGateway;
	protected $limit;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function fetchLimit()
	{	
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->limit(5);
		});
		return $resultSet;
	}

}