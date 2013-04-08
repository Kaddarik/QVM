<?php
namespace Qvm\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryTable
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

	public function getCategory($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id_category' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	/**
	 * Insertion d'une catégorie
	 * @param Category $category
	 * @throws \Exception
	 */
	public function saveCategory(Category $category)
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