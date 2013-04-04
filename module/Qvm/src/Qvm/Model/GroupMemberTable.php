<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;

class GroupMemberTable
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
	
	public function updateGroupsPrivateInvit($idPerson, $idGroup){
		/*$update = new Update();
			$update->table('groupmember')
		->set(array('id_pending' => 0))
		->where(array('groupmember.id_person' => $idPerson))
		->where(array('groupmember.id_group' => $idGroup));*/
	
		$data = array('id_pending' => 0);
		$this->tableGateway->update($data, array('id_person' => $idPerson, 'id_group' => $idGroup));
			
	
	}
}