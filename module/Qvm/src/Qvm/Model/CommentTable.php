<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CommentTable
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
	

	public function getComment($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_comment' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getCommentByEvent($id)
	{
		$select = new Select;
		$select->from('comment');
		$select->where->equalTo('id_event', $id);
		return $this->tableGateway->selectWith($select);
	}

	public function saveComment(Comment $comment)
	{
		$data = array(
				'body'  => $comment->body,
				'datetime' => 'now()',
				'id_event' =>  $comment->id_event,
				'id_person' => $comment->id_person,
		);

		$id_comment = (int)$comment->id_comment;
		if ($id_comment == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getComment($id_comment)) {
				$this->tableGateway->update($data, array('id_comment' => $id_comment));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
}