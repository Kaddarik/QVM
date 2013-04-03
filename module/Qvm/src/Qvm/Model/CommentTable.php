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

	public function getCommentByEvent($id)
	{
		$select = new Select;
		$select->from('comment');
		$select->where->equalTo('id_event', $id);
		return $this->tableGateway->selectWith($select);
	}

	public function saveComment(Category $category)
	{
		$data = array(
				'label'  => $category->label,
				'displaying_order' => '98',
				'icon_class'  => $category->icon_class,

		);

		$id_category = (int)$category->id_category;
		if ($id_category == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getCategory($id_category)) {
				$this->tableGateway->update($data, array('id_category' => $id_category));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
}