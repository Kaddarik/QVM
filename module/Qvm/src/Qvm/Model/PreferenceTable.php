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

	
	/**
	 * Récupération de la préférence de vote d'un utilisateur à une activité
	 * @param unknown $id_activity
	 * @param unknown $id_person
	 * @return unknown
	 */
	public function getPreferenceByActivityAndPerson($id_activity, $user_id)
	{
		$select = new Select;
		$select->from('preference');
		$select->where->equalTo('preference.id_activity', $id_activity);
		$select->where->equalTo('preference.user_id', $user_id);
		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();
		return $row;
	}
	
	/**
	 * Insertion ou modification de la préférence de vote d'un utilisateur à une activité
	 * @param unknown $id_activity
	 * @param unknown $vote
	 * @param unknown $user_id
	 */
	public function savePreference($id_activity, $vote, $user_id)
	{
		$data = array(
				'id_activity'  => $id_activity,
				'user_id' =>  $user_id,
				'vote' => $vote,
		);
		
		if ($this->getPreferenceByActivityAndPerson($id_activity, $user_id)) {
			$this->tableGateway->update($data, array('user_id' => $user_id, 'id_activity' => $id_activity));
		} else {
			$this->tableGateway->insert($data);
		}
	}

}