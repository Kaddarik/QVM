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
	
	public function updateGroupsPrivateInvitValid($idPerson, $idGroup){
		$data = array('id_pending' => 0);
		$this->tableGateway->update($data, array('id_person' => $idPerson, 'id_group' => $idGroup));
	}
	
	public function updateGroupsPrivateInvitRefus($idPerson, $idGroup){
		$data = array('id_pending' => 2);
		$this->tableGateway->update($data, array('id_person' => $idPerson, 'id_group' => $idGroup));
	}
	
	/*public function updateGroupsPublicRejoindre($idPerson, $idGroup){
		$data = array('id_pending' => 0);
		$this->tableGateway->update($data, array('id_person' => $idPerson, 'id_group' => $idGroup));
	}*/
}
