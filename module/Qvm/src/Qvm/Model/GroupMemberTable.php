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
	
	/**
	 * Mise � jour du statut de l'acceptation
	 * @param unknown $user_id
	 * @param unknown $idGroup
	 */
	public function updateGroupsPrivateInvitValid($user_id, $idGroup){
		$data = array('id_pending' => 0);
		$this->tableGateway->update($data, array('user_id' => $user_id, 'id_group' => $idGroup));
	}
	
	/**
	 * Mise � jour du statut par refus
	 * @param unknown $user_id
	 * @param unknown $idGroup
	 */
	public function updateGroupsPrivateInvitRefus($user_id, $idGroup){
		$data = array('id_pending' => 2);
		$this->tableGateway->update($data, array('user_id' => $user_id, 'id_group' => $idGroup));
	}
	
	/*public function updateGroupsPublicRejoindre($user_id, $idGroup){
		$data = array('id_pending' => 0);
		$this->tableGateway->update($data, array('user_id' => $user_id, 'id_group' => $idGroup));
	}*/
	
	/**
	 * Insertion d'un administateur
	 * @param unknown $idg
	 * @param unknown $idu
	 */
	public function saveGroupAdmin($idg, $idu)
	{
		$data = array(
				'id_group' => $idg,
				'user_id'  => $idu,
				'is_admin' => 1,
				'id_pending' => 0,
		);
	
		$this->tableGateway->insert($data);
	}
}
