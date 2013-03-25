<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Person implements InputFilterAwareInterface
{
	public $id_person;
	public $surname;
	public $firstname;
	public $password;
	public $mail;
	public $phonenumbre;
	public $id_twitter;
	public $meteo_location;
	public $idsession;
	public $is_sysadmin;
	public $is_pending;

	public function exchangeArray($data)
	{
		$this->id_person     = (isset($data['id_person'])) ? $data['id_person'] : null;
		$this->surname = (isset($data['surname'])) ? $data['surname'] : null;
		$this->firstname  = (isset($data['firstname'])) ? $data['firstname'] : null;
		$this->password  = (isset($data['password'])) ? $data['password'] : null;
		$this->mail  = (isset($data['mail'])) ? $data['mail'] : null;
		$this->phonenumber  = (isset($data['phonenumber'])) ? $data['phonenumber'] : null;
		$this->id_twitter  = (isset($data['id_twitter'])) ? $data['id_twitter'] : null;
		$this->meteo_location  = (isset($data['meteo_location'])) ? $data['meteo_location'] : null;
		$this->idsession  = (isset($data['idsession'])) ? $data['idsession'] : null;
		$this->is_sysadmin  = (isset($data['is_sysadmin'])) ? $data['is_sysadmin'] : null;
		$this->is_pending  = (isset($data['is_pending'])) ? $data['is_pending'] : null;
	} 
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();
		
			$inputFilter->add($factory->createInput(array(
				'name'     => 'id_person',
				'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'surname',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'firstname',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'password',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'mail',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'phonenumber',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'id_twitter',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'meteo_location',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'idsession',
					'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'is_pending',
					'required' => false,
			)));
		
			$inputFilter->add($factory->createInput(array(
					'name'     => 'is_sysadmin',
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