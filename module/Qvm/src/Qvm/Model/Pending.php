<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Pending implements InputFilterAwareInterface
{
	public $id_pending;
	public $label;

	public function exchangeArray($data)
	{
		$this->id_pending  = (isset($data['id_pending'])) ? $data['id_pending'] : null;
		$this->label  = (isset($data['label'])) ? $data['label'] : null;
	} 
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();
		
			$inputFilter->add($factory->createInput(array(
					'name'     => 'id_pending',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'label',
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