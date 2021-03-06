<?php
namespace Qvm\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
	public $user_id;
	public $username;
	public $surname;
	public $firstname;
	public $password;
	public $email;
	public $phonenumbre;
	public $id_twitter;
	public $meteo_location;
	public $idsession;
	public $is_sysadmin;

	public function exchangeArray($data)
	{
		$this->user_id     = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->username = (isset($data['username'])) ? $data['username'] : null;
		$this->surname = (isset($data['surname'])) ? $data['surname'] : null;
		$this->firstname  = (isset($data['firstname'])) ? $data['firstname'] : null;
		$this->password  = (isset($data['password'])) ? $data['password'] : null;
		$this->email  = (isset($data['email'])) ? $data['email'] : null;
		$this->phonenumber  = (isset($data['phonenumber'])) ? $data['phonenumber'] : null;
		$this->id_twitter  = (isset($data['id_twitter'])) ? $data['id_twitter'] : null;
		$this->meteo_location  = (isset($data['meteo_location'])) ? $data['meteo_location'] : null;
		$this->idsession  = (isset($data['idsession'])) ? $data['idsession'] : null;
		$this->is_sysadmin  = (isset($data['is_sysadmin'])) ? $data['is_sysadmin'] : null;
	} 
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();
		
			$inputFilter->add($factory->createInput(array(
				'name'     => 'user_id',
				'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'username',
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
					'name'     => 'email',
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
					'name'     => 'is_sysadmin',
					'required' => false,
			)));
			
			$this->inputFilter = $inputFilter;
		}
        return $this->inputFilter;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	


}