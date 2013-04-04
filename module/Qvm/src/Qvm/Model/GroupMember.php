<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class GroupMember implements InputFilterAwareInterface
{
	public $id_group;
	public $id_person;
	public $is_admin;
	public $id_pending;

	public function exchangeArray($data)
	{
		$this->id_group = (isset($data['id_group'])) ? $data['id_group'] : null;
		$this->id_person     = (isset($data['id_person'])) ? $data['id_person'] : null;
		$this->is_admin  = (isset($data['is_admin'])) ? $data['is_admin'] : null;
		$this->id_pending  = (isset($data['id_pending'])) ? $data['id_pending'] : null;
	} 
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();
		
			$inputFilter->add($factory->createInput(array(
					'name'     => 'id_group',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'id_person',
				'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'is_admin',
					'required' => false,
			)));
		
			$inputFilter->add($factory->createInput(array(
					'name'     => 'id_pending',
					'required' => false,
			)));
		}
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}