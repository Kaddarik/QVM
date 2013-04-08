<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;

class UserTable
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
	 * Récupération des membres d'un groupe
	 * @param unknown $idGroupe
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function getMembersByGroup($idGroupe){
		$select = new Select;
		$select->columns(array('firstname', 'surname', 'email', 'phonenumber'))->from('user')
		->join('groupmember', 'user.user_id = groupmember.user_id', array('is_admin'))
		->order('is_admin DESC')
		->join('group', 'groupmember.id_group = group.id_group', array())
		->where(array('group.id_group' => $idGroupe))
		->where->equalTo('groupmember.id_pending', 0);
		$adapter = $this->tableGateway->getAdapter();
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		$resultSet->next();
		return $resultSet;
	}

	public function getUser($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('user_id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	
	/**
	 * Insertion d'un utilisateur
	 * @param User $user
	 * @throws \Exception
	 */
	public function saveUser(User $user)
	{
		$data = array(
				'surname' => $user->surname,
				'firstname'  => $user->firstname,
				'password' => $user->password,
				'email' => $user->email,
				'phonenumber' => $user->phonenumber,
		);
	
		$id = (int)$user->user_id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getUser($id)) {
				$this->tableGateway->update($data, array('user_id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
}