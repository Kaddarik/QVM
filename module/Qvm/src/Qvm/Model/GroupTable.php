<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class GroupTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			//liste tous les groupes publics
			$select->where->equalTo('is_private', 0);
		});
		return $resultSet;
	}
	
	public function fetchLimit()
	{
		$resultSet = $this->tableGateway->select(function (Select $select){
			$select->order('label')->limit(5);
		});
		return $resultSet;
	}
	
	public function getGroup($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_group' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	/*public function saveAlbum(Album $album)
	{
		$data = array(
				'artist' => $album->artist,
				'title'  => $album->title,
		);

		$id = (int)$album->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getAlbum($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}

	public function deleteAlbum($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}*/
}