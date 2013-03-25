<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;

class ActivityCategoryTable
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

	public function getActivityCategory($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_activity' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveActivityCategory($ida, $idc)
	{
		$data = array(
				'id_activity'  => $ida,
				'id_category'  => $idc,

		);
		$this->tableGateway->insert($data);
	}
}