<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ActivityTable
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
	
	public function fetchLimit()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('title')->limit(10);
		});
		return $resultSet;
	}

	public function getActivity($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_activity' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getLastActivity()
	{
		
		$rowset = $this->tableGateway->getLastInsertValue();
		
		return $rowset;
	}

	public function saveActivity(Activity $activity)
	{
		$data = array(
				'title'  => $activity->title,
				'description'  => $activity->description,
				'id_location' => '1',
		);

		$id_activity = (int)$activity->id_activity;
		if ($id_activity == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getActivity($id_activity)) {
				$this->tableGateway->update($data, array('id_activity' => $id_activity));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}

	/*public function deleteAlbum($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}*/
}