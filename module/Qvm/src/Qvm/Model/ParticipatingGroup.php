<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ParticipatingGroup implements InputFilterAwareInterface
{
	public $id_group;
	public $id_activity;

	public function exchangeArray($data)
	{
		$this->id_group = (isset($data['id_group'])) ? $data['id_group'] : null;
		$this->id_activity     = (isset($data['id_activity'])) ? $data['id_activity'] : null;
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
				'name'     => 'id_activity',
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