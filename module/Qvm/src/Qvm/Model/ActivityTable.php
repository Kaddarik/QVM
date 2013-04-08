<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;

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
	
	/**
	 * R�cup�ration de la derni�re activit� ins�r�e
	 * @return unknown
	 */
	public function getLastActivity()
	{
		
		$rowset = $this->tableGateway->getLastInsertValue();
		
		return $rowset;
	}
	
	/**
	 * Insertion d'une nouvelle activit�
	 * @param Activity $activity
	 * @throws \Exception
	 */
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
	
	/**
	 * R�cup�ration des activit�s par groupe
	 * @param unknown $idGroupe
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getActivitesByGroup($idGroupe){
		$select = new Select;
		$select->columns(array('id_activity', 'title'))->from('activity')
		->join('participatinggroup', 'activity.id_activity = participatinggroup.id_activity', array())
		->join('group', 'group.id_group = participatinggroup.id_group', array())
		->where(array('group.id_group' => $idGroupe));
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